<?php
/**
 * List all users
 *
 * @author BYVoid
 */
class MDL_User_List extends MDL_User_Common
{
	public static function getList($page,$meta=array())
	{
		$config = MDL_Config::getInstance();
		$page_size = $config->getVar('user_list_page_size');
		
		$sql = 'select `user_id`,`user_name`,`user_nickname`';
		foreach($meta as $item)
		{
			$sql.= ','.self::getMetaSQL($item);
		}
		$sql.='from ' .DB_TABLE_USER;
		
		return MDL_List::getList($sql,$page,$page_size);
	}

}