<?php

class MDL_Contest_List extends MDL_List
{
	function def()
	{
		$config_oi = array
		(
			'time' => array
			(
				'sign_up_start' => 0,
				'sign_up_end' => time() + 1000,
				'contest_start' => time() + 500,
				'contest_end' => time() + 10000,
				'contest_time_limit' => 0,
			),
			'rules' => array
			(
				'case_point' => true,
				'penalty_time' => '0',
			),
			'permissions' => array
			(
				'during' => array
				(
					'record_display' => 31,
					'rank_display' => 0,
				),
				'end' => array
				(
					'record_display' => 31,
					'rank_display' => 0,
				),
			),
			'others' => array
			(
				'realtime_judge' => true,
			),
		);
	}

	public function __construct($page = 0, $page_size = 0)
	{
		if ($page_size == 0)
			$page_size = MDL_Config::getInstance()->problem_list_page_size;

		$this->setPageSize($page_size);
		$this->setCurrentPage($page);

		$sql = 'select * from '. DB_TABLE_CONTEST;
		$this->setSQLPrefix($sql);

		parent::__construct();
	}

	public function getList()
	{
		parent::getList();
		foreach ($this->list as $i => $item)
		{
			$this->list[$i] = new MDL_Contest($item['contest_id'], $item['contest_config'], $item['contest_status']);
		}
		return $this->list;
	}
}