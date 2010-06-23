<?php
class MDL_Record
{
	public static function exists($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_id` from'. DB_TABLE_RECORD .' where `record_id` = :record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$rs = $stmt->fetch();
		return !empty($rs);
	}
	
	public static function getRecordDetial($record_id)
	{
		$rmeta = new MDL_Record_Meta($record_id);
		$record_detail = $rmeta->getAll();
		
		/* 獲取顯示權限信息 */
		$display = new MDL_Record_DisplayConfig($record_detail['display']);
		
		$record_detail['display'] = $display;
		
		/* 顯示結果信息 */
		$record_detail['source_length'] = strlen($record_detail['source']);
		unset($record_detail['source']);
		
		if (!$display->showRunResult())
		{
			unset($record_detail['status']);
			unset($record_detail['result_text']);
			unset($record_detail['score']);
			unset($record_detail['time']);
			unset($record_detail['memory']);
		}
		
		if (isset($record_detail['result']))
		{
			$record_detail['result'] = BFL_XML::XML2Array($record_detail['result']);
			
			/* 顯示編譯信息 */
			if (!$display->showCompileResult() && isset($record_detail['result']['compile']))
				unset($record_detail['result']['compile']);
			
			/* 顯示測試點信息 */
			if (!$display->showCaseResult() && isset($result['execute']))
				unset($record_detail['result']['execute']);
		}
		
		return $record_detail;
	}
	
	public static function getRecord($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_RECORD.' where `record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$record = $stmt->fetch();

		if (empty($record))
		{
			throw new MDL_Exception_Record('record_id');
		}

		$record_detail = self::getRecordDetial($record_id);
		$record['detail'] = $record_detail;
		
		//Get problem name
		$prob_id = $record['record_prob_id'];
		$prob_names = MDL_Problem_Show::getProblemName($prob_id);
		$record['prob_name'] = $prob_names['prob_name'];
		$record['prob_title'] = $prob_names['prob_title'];
		
		//Get user name
		$user_id = $record['record_user_id'];
		$user_names = MDL_User_Detail::getUserName($user_id);
		$record['user_name'] = $user_names['user_name'];
		$record['user_nickname'] = $user_names['user_nickname'];
		
		return $record;
	}
	
	public static function getRecordSource($record_id)
	{
		if (!self::exists($record_id))
		{
			throw new MDL_Exception_Record('record_id');
		}
		
		$rmeta = new MDL_Record_Meta($record_id);
		$display = new MDL_Record_DisplayConfig($rmeta->getVar('display'));
		
		if (!$display->showCodeToPublic())
		{
			if (!$display->showCodeToOwner() ||
					self::getUserID($record_id) != BFL_ACL::getInstance()->getUserID())
				throw new MDL_Exception_Record('deny');
		}
		
		$record['source'] = $rmeta->getVar('source');
		$record['record_id'] = $record_id;
		return $record;
	}
	
	public static function completed($record_id)
	{
		$rmeta = new MDL_Record_Meta($record_id);
		$status = (int)$rmeta->getVar('status');
		return $status == MDL_Judge_Record::STATUS_STOPPED;
	}
	
	public static function getUserID($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_user_id` from'. DB_TABLE_RECORD .' where `record_id` = :record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$rs = $stmt->fetch();
		if (empty($rs))
			throw new MDL_Exception_Record('record_id');
		return $rs['record_user_id'];
	}
	
	public static function getJudgerID($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_judger_id` from'. DB_TABLE_RECORD .' where `record_id` = :record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$rs = $stmt->fetch();
		if (empty($rs))
			throw new MDL_Exception_Record('record_id');
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