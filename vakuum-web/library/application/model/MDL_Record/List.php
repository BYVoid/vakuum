<?php
/**
 * List all records
 *
 * @author BYVoid
 */
class MDL_Record_List
{
	public static function getList($page,$page_size = 0)
	{
		$config = MDL_Config::getInstance();
		if ($page_size == 0)
			$page_size = $config->getVar('record_list_page_size');

		$sql = 'select * from ' . DB_TABLE_RECORD;
		$rs = MDL_List::getList($sql,$page,$page_size);


		foreach($rs['list'] as $i => $value)
		{
			$rs['list'][$i] = new MDL_Record($value['record_id'],MDL_Record::GET_NONE,array(
				'user_id' => $value['record_user_id'],
				'prob_id' => $value['record_prob_id'],
				'judger_id' => $value['record_judger_id'],
			));
		}

		return $rs;
	}

}