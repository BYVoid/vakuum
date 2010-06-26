<?php
/**
 * List all users
 *
 * @author BYVoid
 */
class MDL_User_List extends MDL_User_Common
{
	public static function getList($page)
	{
		$config = MDL_Config::getInstance();
		$page_size = $config->getVar('user_list_page_size');

		$sql = 'select `user_id`,`user_name`,`user_nickname` from '. DB_TABLE_USER;

		$list = MDL_List::getList($sql,$page,$page_size);

		foreach ($list['list'] as $i => $item)
		{
			$list['list'][$i] = new MDL_User($item['user_id'], MDL_User::ID_USER_ID, MDL_User::GET_NONE ,
			array (
				'user_name' => $item['user_name'],
				'user_nickname' => $item['user_nickname'],
			));
		}

		return $list;
	}

}