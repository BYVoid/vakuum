<?php
/**
 * Show a record
 *
 * @author BYVoid
 */
class MDL_Record_Detail
{
	/**
	 * Get User By Name
	 * @param int $user_name User Name
	 * @return array User
	 */
	public static function getRecord($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_RECORD.' where `record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		$record = $stmt->fetch();

		if (empty($record))
		{
			throw new MDL_Exception_Record('id');
		}

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
		$record['detail'] = $record_detail;
		
		if (isset($record_detail['result']))
		{
			$result = BFL_XML::XML2Array($record_detail['result']);
			unset($record_detail['result']);
			
			/* 顯示編譯信息 */
			if ($display->showCompileResult() && isset($result['compile']))
				$record['compile'] = $result['compile'];
			
			/* 顯示測試點信息 */
			if ($display->showCaseResult() && isset($result['execute']))
				$record['execute'] = $result['execute']['case'];
			
		}
		
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
	
	public static function completed($record_id)
	{
		$rmeta = new MDL_Record_Meta($record_id);
		$status = (int)$rmeta->getVar('status');
		return $status == MDL_Judge_Record::STATUS_STOPPED;
	}
}