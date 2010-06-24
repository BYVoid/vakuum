<?php
/**
 * Edit a problem
 *
 * @author BYVoid
 */
class MDL_Problem_Edit
{
	/**
	 * getNextProblemID
	 * @return int
	 */
	public static function getNextProblemID()
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select MAX(`prob_id`) as maxid from '.DB_TABLE_PROB);
		$stmt->execute();
		$rs = $stmt->fetch();
		if (empty($rs))
			$prob_id = 1001;
		else
			$prob_id = $rs['maxid'] + 1;
		return $prob_id;
	}
	
	/**
	 * add
	 * @param array $problem
	 * @return void
	 */
	public static function add($problem)
	{
		self::verify($problem);
		
		$db = BFL_Database :: getInstance();
		$meta = array
		(
			'prob_id' => ':prob_id' ,
			'prob_name' => ':prob_name',
			'prob_title' => ':prob_title',
			'prob_content' => ':prob_content',
		);
		
		$stmt = $db->insert(DB_TABLE_PROB , $meta);
		$stmt->bindParam(':prob_id', $problem['prob_id']);
		$stmt->bindParam(':prob_name', $problem['prob_name']);
		$stmt->bindParam(':prob_title', $problem['prob_title']);
		$stmt->bindParam(':prob_content', $problem['prob_content']);
		$stmt->execute();
		
		$prob_meta = new MDL_Problem_Meta($problem['prob_id']);
		$prob_meta->setVar('adding_time',time());
		$prob_meta->setVar('data_config','');
		$prob_meta->setVar('display',$problem['display']);
	}
	
	/**
	 * edit
	 * @param array $problem
	 * @return void
	 */
	public static function edit($problem)
	{
		self::verify($problem);
		
		$db = BFL_Database::getInstance();
		$meta = array
		(
			'`prob_name` = :prob_name',
			'`prob_title` = :prob_title',
			'`prob_content` = :prob_content',
		);
		
		$stmt = $db->update(DB_TABLE_PROB ,$meta ,'where `prob_id`=:prob_id ');
		$stmt->bindParam(':prob_id', $problem['prob_id']);
		$stmt->bindParam(':prob_name', $problem['prob_name']);
		$stmt->bindParam(':prob_title', $problem['prob_title']);
		$stmt->bindParam(':prob_content', $problem['prob_content']);
		$stmt->execute();
		
		$prob_meta = new MDL_Problem_Meta($problem['prob_id']);
		$prob_meta->setVar('display',$problem['display']);
	}
	
	/**
	 * remove
	 * @param int $prob_id
	 * @return void
	 */
	public static function remove($prob_id)
	{
		$db = BFL_Database::getInstance();
		$db->beginTransaction();
		
		$stmt = $db->delete(DB_TABLE_PROB,'where `prob_id`=:prob_id');
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->execute();
		
		$stmt = $db->delete(DB_TABLE_PROBMETA,'where `pmeta_prob_id`=:prob_id');
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->execute();
		
		$records=MDL_Problem_List::getRecords($prob_id);
		
		foreach($records as $record)
		{
			MDL_Record_Edit::delete($record['record_id']);
		}
		
		$db->commit();
	}
	
	/**
	 * verify
	 * @param array $problem
	 * @return void
	 */
	private static function verify($problem)
	{
		if (!isset($problem['prob_id']) || !is_numeric($problem['prob_id']))
			throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_PROB_ID);
		
		$len = strlen($problem['prob_name']);
		if (!isset($problem['prob_name']) || $len > 32 || $len == 0 )
			throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_PROB_NAME);
		
		$len = strlen($problem['prob_title']);
		if (!isset($problem['prob_title']) || $len > 32 || $len == 0 )
			throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_PROB_TITLE);
	}
}