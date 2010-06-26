<?php
class MDL_Problem_List
{
	public static function getList($page,$check_display = false)
	{
		$config = MDL_Config::getInstance();
		$page_size = $config->getVar('problem_list_page_size');

		$sql = 'select `prob_id` from '.DB_TABLE_PROB;

		$list = MDL_List::getList($sql,$page,$page_size);

		foreach ($list['list'] as $i => $item)
		{
			$list['list'][$i] = new MDL_Problem($item['prob_id']);
		}

		return $list;
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