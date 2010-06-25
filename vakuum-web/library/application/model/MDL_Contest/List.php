<?php

class MDL_Contest_List extends MDL_Contest
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
		
	public static function getList($page,$check_display = false)
	{
		$config = MDL_Config::getInstance();
		$page_size = $config->getVar('contest_list_page_size');
		
		$sql = 'select * from '.DB_TABLE_CONTEST;

		$listinfo = MDL_List::getList($sql,$page,$page_size);
		
		foreach($listinfo['list'] as $i => $item)
		{
			$listinfo['list'][$i] = new MDL_Contest($item['contest_id'], $item['contest_config'], $item['contest_status']);
		}
		
		if ($check_display)
		{
			
		}
		
		return $listinfo;
	}
}