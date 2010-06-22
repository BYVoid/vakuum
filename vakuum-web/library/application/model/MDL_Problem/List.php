<?php
/**
 * List all problems
 *
 * @author BYVoid
 */
class MDL_Problem_List
{
	protected static function getMetaSQL($pmeta_key,$display='')
	{
		$sql = '( select `pmeta_value` from '. DB_TABLE_PROBMETA .' '.
				'where `pmeta_prob_id`=`prob_id` and `pmeta_key`=\''. $pmeta_key .'\' )';
		if ($display != '')
		{
			$sql .= 'as `'. $display .'` ';
		}
		return $sql;
	}
		
	public static function getList($page,$check_display = false)
	{
		$config = MDL_Config::getInstance();
		$page_size = $config->getVar('problem_list_page_size');
		
		$sql = 'select `prob_id`,`prob_name`,`prob_title`,'. self::getMetaSQL('adding_time','adding_time') .
				'from ' .DB_TABLE_PROB;
		if ($check_display)
		{
			$sql .= ' where '.self::getMetaSQL('display').' = 1';
		}
		return MDL_List::getList($sql,$page,$page_size);
	}

	public static function getRecords($prob_id)
	{
		$db = BFL_Database::getInstance();
		$stmt = $db->factory('select `record_id` from '.DB_TABLE_RECORD.' where `record_prob_id`=:prob_id');
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->execute();
		$records = $stmt->fetchAll();
		return $records;
	}
}