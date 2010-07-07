<?php
/**
 * List all judgers
 *
 * @author BYVoid
 */
class MDL_Judger_List extends MDL_List
{
	public function __construct($page = 0, $page_size = 0)
	{
		if ($page_size == 0)
			$page_size = MDL_Config::getInstance()->judger_list_page_size;

		$this->setPageSize($page_size);
		$this->setCurrentPage($page);

		$sql = 'select * from '. DB_TABLE_JUDGER;
		$this->setSQLPrefix($sql);

		parent::__construct();
	}

	public function getList()
	{
		parent::getList();
		foreach ($this->list as $i => $judger)
		{
			$this->list[$i] = new MDL_Judger($judger['judger_id'], MDL_Judger::GET_NONE, array(
				'judger_name' => $judger['judger_name'],
				'judger_priority' => $judger['judger_priority'],
				'judger_enabled' => $judger['judger_enabled'],
				'judger_available' => $judger['judger_available'],
				'judger_count' => $judger['judger_count'],
				'judger_config' => $judger['judger_config'],
			));
		}
		return $this->list;
	}
}