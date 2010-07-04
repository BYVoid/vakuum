<?php
class MDL_Problem_List extends MDL_List
{
	public function __construct($page = 0, $page_size = 0)
	{
		if ($page_size == 0)
			$page_size = MDL_Config::getInstance()->problem_list_page_size;

		$this->setPageSize($page_size);
		$this->setCurrentPage($page);

		$sql = 'select `prob_id` from '. DB_TABLE_PROB;
		$this->setSQLPrefix($sql);

		parent::__construct();
	}

	public function getList()
	{
		parent::getList();
		foreach ($this->list as $i => $item)
		{
			$this->list[$i] = new MDL_Problem($item['prob_id']);
		}
		return $this->list;
	}
}