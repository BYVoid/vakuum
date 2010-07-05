<?php
/**
 * List all users
 *
 * @author BYVoid
 */
class MDL_User_List extends MDL_List
{
	protected $plist = NULL;

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
		if ($this->plist == NULL)
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
			$this->plist = $this->list;
		}
		return $this->plist;
	}

	private function cmpByAccepted($a ,$b)
	{
		$rtv = $a->getAcceptedProblemsCount() - $b->getAcceptedProblemsCount();
		if ($rtv == 0)
			$rtv = $a->getAcceptedRate() < $b->getAcceptedRate() ? -1 : 1;

		if ($this->tmp_order == 'desc')
			$rtv = 0 - $rtv;
		return $rtv;
	}

	public function setOrder($order_by, $order)
	{
		$this->plist = NULL;
		if ($order_by == 'ac')
		{
			if ($order != 'asc')
				$order = 'desc';
			$key = 'user_list_'.$order_by.'_'.$order.'_'.$this->getCurrentPage();

			$cache = MDL_Cache::getInstance();
			if (!isset($cache->$key))
			{
				$sql = 'select `user_id` from '. DB_TABLE_USER;
				$db = BFL_Database::getInstance();
				$stmt = $db->factory($sql);
				$stmt->execute();

				$user_records = array();
				while ($user = $stmt->fetch())
					$user_records[] = new MDL_User_Record(new MDL_User($user['user_id']));

				$this->tmp_order = $order;
				usort($user_records,array($this,'cmpByAccepted'));
				unset($this->tmp_order);

				$users = array();
				foreach ($user_records as $user_record)
					$users[] = $user_record->getUser();

				$cache->$key = $users;
			}

			$users = $cache->$key;

			$this->plist = $this->separatePage($users);
		}
	}
}