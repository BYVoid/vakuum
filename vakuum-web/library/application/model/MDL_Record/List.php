<?php
/**
 * List all records
 *
 * @author BYVoid
 */
class MDL_Record_List
{
	protected static function getMetaSQL($rmeta_key,$display='')
	{
		if ($display =='')
			$display = $rmeta_key;
		return '( select `rmeta_value` from '. DB_TABLE_RECORDMETA .' '.
				'where `rmeta_record_id`=`record_id` and `rmeta_key`=\''. $rmeta_key .'\' ) as `'
				. $display .'` ';
	}
		
	public static function getList($page,$page_size=0)
	{
		$config = MDL_Config::getInstance();
		if ($page_size == 0)
			$page_size = $config->getVar('record_list_page_size');
		
		$sql = 'select 
			`record_id`,
			`prob_id`,
			`prob_name`,
			`prob_title`,
			`user_id`,
			`user_name`,
			`user_nickname`,
			`record_judger_id` as `judger_id` from ' .
			DB_TABLE_RECORD.','. DB_TABLE_PROB. ','. DB_TABLE_USER .
			'where (record_prob_id = prob_id) and (record_user_id = user_id)'.
			'order by record_id desc';
		
		
		$rs = MDL_List::getList($sql,$page,$page_size);
		foreach($rs['list'] as $key => $value)
		{
			$rmeta = new MDL_Record_Meta($value['record_id']);
			$rs['list'][$key]['other'] = $rmeta->getAll();
		}

		return $rs;
	}

}