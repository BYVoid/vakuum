<?php
class MDL_Judger_Process
{
	public static function processTaskQueue()
	{
		set_time_limit(0);
		ignore_user_abort(1);

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
		$judger = MDL_Judger_Set::getAvailableJudger();
		if ($judger == NULL)
		{
			$db->unlock();
			return 'nojudger';
		}

		$judger_id = $judger->getID();



		$task = self::getTask();
		if (!$task)
		{
			$db->unlock();
			return 'notask';
		}

		//Pend task
		MDL_Judge_Record::pend($task['record_id'],$judger_id);

		//lock judger
		$judger->lock();
		$db->unlock();

		//upload source
		$task['src_name']= self::getSrcname($task['task_name'],$task['language']);
		MDL_Judger_Transmit::sendTask($judger,$task['task_name'],$task['source'],$task['src_name']);

		//send judge request
		$config = MDL_Config::getInstance();
		MDL_Judger_Access::sendRequest($judger ,$task);
	}

	public static function getSrcname($task_name,$lang)
	{
		switch ($lang)
		{
			case 'c':
				$suffix = '.c';
				break;
			case 'cpp':
				$suffix = '.cpp';
				break;
			case 'pas':
				$suffix = '.pas';
				break;
		}
		return $task_name . $suffix;
	}

	public static function getTask()
	{
		//find records whose record_judger_id = 0

		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_id`,`record_prob_id` from '.DB_TABLE_RECORD.
			' where `record_judger_id` = 0 order by `record_id` asc');

		$stmt->execute();
		$task = $stmt->fetch();

		if (!$task)
		{
			return array();
		}

		$record_meta = new MDL_Record_Meta($task['record_id']);
		$task['language'] = $record_meta->getVar('lang');
		$task['source'] = $record_meta->getVar('source');
		$submit_time = $record_meta->getVar('submit_time');
		$task['task_name'] = 'vkm_'. $task['record_id'];

		$prob_names = MDL_Problem_Show::getProblemName($task['record_prob_id']);
		$task['prob_name'] = $prob_names['prob_name'];

		unset($task['record_prob_id']);

		return $task;
	}
}