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
		$judger = self::getAvailableJudger();
		if (!$judger)
		{
			$db->unlock();
			return 'nojudger';
		}
		$judger_url = $judger['url'];
		$judger_public_key = $judger['public_key'];
		
		$task = MDL_Record::getTask();
		if (!$task)
		{
			$db->unlock();
			return 'notask';
		}
		
		//Pend task
		MDL_Judge_Record::pend($task['record_id'],$judger['judger_id']);
		
		//lock judger
		MDL_Judger::lock($judger['judger_id']);
		$db->unlock();
		
		//upload source
		$task['src_name']= MDL_Record::getSrcname($task['task_name'],$task['language']);
		MDL_Judger_Transmit::sendTask($judger,$task['task_name'],$task['source'],$task['src_name']);
		
		//send judge request
		$config = MDL_Config::getInstance();
		$return_url = $config->getVar('judge_return_site').MDL_Locator::getInstance()->getURL('judge_return');
		MDL_Judger_Access::sendRequest($judger_url,$judger_public_key,$task,$return_url);
	}

	private static function getAvailableJudger()
	{
		//Get judger
		$db = BFL_Database :: getInstance();
		
		$stmt = $db->factory('select `judger_id`,`judger_config` from '.DB_TABLE_JUDGER.
			' where `judger_enabled` = 1 and `judger_available` = 1 order by judger_priority asc');
		$stmt->execute();

		$rs = $stmt->fetch();
		if (empty($rs))
		{
			return array(); //No available judger
		}

		//Parse the XML config
		$judger = BFL_XML::XML2Array($rs['judger_config']);
		$judger['judger_id'] = $rs['judger_id'];
		
		return $judger;
	}
	
}