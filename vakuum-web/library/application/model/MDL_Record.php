<?php
class MDL_Record
{
	public static function getJudgerID($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_judger_id` from'. DB_TABLE_RECORD .' where `record_id` = :record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$rs = $stmt->fetch();
		if (empty($rs))
			throw new MDL_Exception_Record('id');
		return $rs['record_judger_id'];
	}
	
	public static function getTaskName($record_id)
	{
		return 'vkm_'.$record_id;
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
		$task['task_name'] = self::getTaskName($task['record_id'],$submit_time);
		
		$prob_names = MDL_Problem_Show::getProblemName($task['record_prob_id']);
		$task['prob_name'] = $prob_names['prob_name'];
		
		unset($task['record_prob_id']);
		
		return $task;
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
}