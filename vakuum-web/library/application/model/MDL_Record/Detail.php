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
	public static function getInfo($record_id)
	{
		$rs = self::getRecordBase($record_id);

		if (empty($rs))
		{
			throw new MDL_Exception_Record('id');
		}

		$rmeta = new MDL_Record_Meta($record_id);
		$rs['detail'] = $rmeta->getAll();
		if (isset($rs['detail']['result']))
		{
			$result = BFL_XML::XML2Array($rs['detail']['result']);
			$rs['compile'] = $result['compile'];
			if (isset($result['execute']))
				$rs['execute'] = $result['execute']['case'];
		}
		
		//Get problem name
		$prob_id = $rs['record_prob_id'];
		$prob_names = MDL_Problem_Show::getProblemName($prob_id);
		$rs['prob_name'] = $prob_names['prob_name'];
		$rs['prob_title'] = $prob_names['prob_title'];
		
		//Get user name
		$user_id = $rs['record_user_id'];
		$user_names = MDL_User_Detail::getUserName($user_id);
		$rs['user_name'] = $user_names['user_name'];
		$rs['user_nickname'] = $user_names['user_nickname'];
		
		return $rs;
	}
	
	public static function getRecordBase($record_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_RECORD.' where `record_id`=:record_id');
		$stmt->bindParam(':record_id', $record_id);
		$stmt->execute();
		return $stmt->fetch();
	}
	
	public static function completed($record_id)
	{
		$rmeta = new MDL_Record_Meta($record_id);
		$status = (int)$rmeta->getVar('status');
		return $status == MDL_Judge_Record::STATUS_STOPPED;
	}
}