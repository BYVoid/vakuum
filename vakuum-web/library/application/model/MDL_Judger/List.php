<?php
/**
 * List all judgers
 *
 * @author BYVoid
 */
class MDL_Judger_List extends MDL_Judger
{
	public static function getList($page,$page_size=0)
	{
		$config = MDL_Config::getInstance();
		if ($page_size == 0)
			$page_size = $config->getVar('judger_list_page_size');
		
		$sql = 'select * from ' .DB_TABLE_JUDGER;
		
		$rs = MDL_List::getList($sql,$page,$page_size);
		
		foreach($rs['list'] as $key => $value)
		{
			$rs['list'][$key]['judger_config'] = self::decodeConfig($rs['list'][$key]['judger_config']);
		}

		return $rs;
	}
}