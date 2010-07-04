<?php
/**
 * List all users
 *
 * @author BYVoid
 */
class MDL_User_List extends MDL_List
{
	public function __construct($page = 0, $page_size = 0)
	{
		if ($page_size == 0)
			$page_size = MDL_Config::getInstance()->user_list_page_size;

		$this->setPageSize($page_size);
		$this->setCurrentPage($page);

		$sql = 'select `user_id`,`user_name`,`user_nickname` from '. DB_TABLE_USER;
		$this->setSQLPrefix($sql);

		parent::__construct();
	}

	public function getList()
	{
		parent::getList();
		foreach ($this->list as $i => $item)
		{
			$this->list[$i] = new MDL_User($item['user_id'], MDL_User::ID_USER_ID, MDL_User::GET_NONE ,
			array (
				'user_name' => $item['user_name'],
				'user_nickname' => $item['user_nickname'],
			));
		}
		return $this->list;
	}
}