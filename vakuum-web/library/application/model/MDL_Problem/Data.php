<?php
/**
 * Edit a problem data
 *
 * @author BYVoid
 */
class MDL_Problem_Data
{

	public static function getDataConfig($prob_id,$prob_name,$prob_title)
	{
		$prob_meta = new MDL_Problem_Meta($prob_id);
		$data_config_xml = $prob_meta->getVar('data_config');
		if ($data_config_xml != '')
		{
			$data_config = BFL_XML::XML2Array($data_config_xml);
		}
		else
			$data_config = array();

		$new_data_config = array
		(
			'id' => $prob_id,
			'name' => $prob_name,
			'title' => $prob_title,
			'input' => isset($data_config['input'])?$data_config['input']:"",
			'output' => isset($data_config['output'])?$data_config['output']:"",
			'checker' => isset($data_config['checker'])?$data_config['checker']:"std_checker",

			'time_limit' => isset($data_config['time_limit'])?$data_config['time_limit']:"",
			'memory_limit' => isset($data_config['memory_limit'])?$data_config['memory_limit']:"",
			'output_limit' => isset($data_config['output_limit'])?$data_config['output_limit']:"",
		);

		$new_data_config['checker'] = array
		(
			'name' => isset($data_config['checker']['name'])?$data_config['checker']['name']:"std_checker",
			'type' => isset($data_config['checker']['type'])?$data_config['checker']['type']:"standard",
			'custom' => array
			(
				'source' => isset($data_config['checker']['custom']['source'])?$data_config['checker']['custom']['source']:"",
				'language' => isset($data_config['checker']['custom']['language'])?$data_config['checker']['custom']['language']:"",
			),
		);

		if (isset($data_config['additional_file']))
		{
			if (!is_array($data_config['additional_file']))
				$data_config['additional_file'] = array($data_config['additional_file']);
			$new_data_config['additional_file'] = $data_config['additional_file'];
		}
		else
			$new_data_config['additional_file'] = array();

		if (isset($data_config['case']))
		{
			if (!isset($data_config['case'][0]))
				$data_config['case'] = array($data_config['case']);
			foreach($data_config['case'] as $item)
			{
				$newcase = array();
				foreach (array('input','output','time_limit','memory_limit','output_limit') as $key)
				{
					if (isset($item[$key]))
						$newcase[$key] = $item[$key];
				}
				$new_data_config['case'][] = $newcase;
			}
		}
		else
			$new_data_config['case'] = array();


		if (isset($data_config['version']))
			$new_data_config['version'] = $data_config['version'];
		else
			$new_data_config['version'] = 0;

		return $new_data_config;
	}

	/**
	 * getNextProblemID
	 * @return int
	 */
	public static function getNextProblemID()
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select MAX(`prob_id`) as maxid from '.DB_TABLE_PROB);
		$stmt->execute();
		$rs = $stmt->fetch();
		if (empty($rs))
			$prob_id = 1001;
		else
			$prob_id = $rs['maxid'] + 1;
		return $prob_id;
	}

	/**
	 * edit
	 * @param array $data_config
	 * @return void
	 */
	public static function edit($data_config)
	{
		$prob_id = $data_config['id'];
		$data_config = self::format($data_config);
		$data_config['version'] = time();
		$data_config = BFL_XML::Array2XML($data_config);
		$prob_meta = new MDL_Problem_Meta($prob_id);
		$prob_meta->setVar('data_config',$data_config);
		$prob_meta->setVar('verified',0);
	}

	/**
	 * format
	 * @param array $data_config
	 * @return void
	 */
	private static function format($data_config)
	{
		$new_data_config = array();
		if (!isset($data_config['id']))
			throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_PROB_ID);
		$prob_id = $data_config['id'];
		$problem_meta = new MDL_Problem_Meta($prob_id);

		if (!isset($data_config['name']) || $data_config['name']=="")
			throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_PROB_NAME);
		$new_data_config['name'] = $data_config['name'];

		foreach(array('input','output','checker') as $key)
		{
			if (!isset($data_config[$key]) || $data_config[$key]=="")
				throw new MDL_Exception_Problem_Edit('invalid_data_'.$key);
			$new_data_config[$key] = $data_config[$key];
		}

		if ($new_data_config['checker']['type'] == 'standard')
			unset($new_data_config['checker']['custom']);

		if (isset($data_config['additional_file']))
			$new_data_config['additional_file'] = $data_config['additional_file'];

		foreach(array('time_limit','memory_limit','output_limit') as $key)
		{
			if (isset($data_config[$key]))
				$new_data_config[$key] = (int)($data_config[$key]);
		}

		if (isset($data_config['case_input']))
		{
			$case_count = count($data_config['case_input']);
			for($i=0;$i<$case_count;$i++)
			{
				foreach(array('input','output') as $key)
				{
					if (!isset($data_config['case_'.$key][$i]) || $data_config['case_'.$key][$i]=="")
						throw new MDL_Exception_Problem_Edit(MDL_Exception_Problem_Edit::INVALID_DATA_CASE);
					$new_data_config['case'][$i][$key] = $data_config['case_'.$key][$i];
				}
				foreach(array('time_limit','memory_limit','output_limit') as $key)
				{
					if (isset($data_config['case_'.$key][$i]) && (int)($data_config['case_'.$key][$i])!=0)
						$new_data_config['case'][$i][$key] = (int)($data_config['case_'.$key][$i]);
				}
			}
		}
		return $new_data_config;
	}
}