<?php
/**
 * List all records
 *
 * @author BYVoid
 */
class MDL_Record_List extends MDL_List
{
	public function __construct($page = 0, $page_size = 0)
	{
		if ($page_size == 0)
			$page_size = MDL_Config::getInstance()->problem_list_page_size;

		$this->setPageSize($page_size);
		$this->setCurrentPage($page);

		$sql = 'select * from '. DB_TABLE_RECORD. ' order by record_id desc';
		$this->setSQLPrefix($sql);

		parent::__construct();
	}

	public function getList()
	{
		parent::getList();
		foreach ($this->list as $i => $item)
		{
			$this->list[$i] = new MDL_Record($item['record_id'], MDL_Record::GET_NONE, array(
				'user_id' => $item['record_user_id'],
				'prob_id' => $item['record_prob_id'],
				'judger_id' => $item['record_judger_id'],
			));
		}
		return $this->list;
	}
}