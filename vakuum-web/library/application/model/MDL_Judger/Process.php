<?php
class MDL_Judger_Process
{
	public static function processTaskQueue()
	{
		$db = BFL_Database :: getInstance();
		$lock_tables = array
		(
			DB_TABLE_JUDGER.' write',
			DB_TABLE_RECORD.' write',
			DB_TABLE_RECORDMETA.' write',
			DB_TABLE_PROB. ' read',
		);
		$db->lock($lock_tables,true);
		
		//Get Available Judger
		$judgers = MDL_Judger::getAvailableJudgers();
		if (empty($judgers))
		{
			$db->unlock();
			return 'nojudger';
		}

		$judger = $judgers[0];
		
		$judger_id = $judger['judger_id'];
		$judger_config = $judger['judger_config'];
		
		$judger_url = $judger_config['url'];
		$judger_public_key = $judger_config['public_key'];
		
		$task = MDL_Record::getTask();
		if (!$task)
		{
			$db->unlock();
			return 'notask';
		}
		
		//Pend task
		MDL_Judge_Record::pend($task['record_id'],$judger_id);
		
		//lock judger
		MDL_Judger::lock($judger_id);
		$db->unlock();
		
		//upload source
		$task['src_name']= MDL_Record::getSrcname($task['task_name'],$task['language']);
		MDL_Judger_Transmit::sendTask($judger_config,$task['task_name'],$task['source'],$task['src_name']);
		
		//send judge request
		$config = MDL_Config::getInstance();
		$return_url = $config->getVar('judge_return_site').MDL_Locator::getInstance()->getURL('judge_return');
		MDL_Judger_Access::sendRequest($judger_url,$judger_public_key,$task,$return_url);
	}
}