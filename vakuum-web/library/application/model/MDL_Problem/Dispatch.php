<?php
/**
 * Edit a problem data
 *
 * @author BYVoid
 */
class MDL_Problem_Dispatch
{
	public static function verify($data_config)
	{
		$verify_result['testdata_path'] = MDL_Config::getInstance()->getVar('judger_testdata').$data_config['name'].'/';
		$verify_result['overall'] = '';
		
		if (!file_exists($verify_result['testdata_path']))
		{
			//testdata_path not exist
			$verify_result['overall'] = 'testdata_path';
			return $verify_result;
		}
		
		
		$hash_code='';
		
		$verify_result['checker'] = true;
		if ($data_config['checker']['type'] == 'custom')
		{
			$checker_file = $verify_result['testdata_path'].$data_config['checker']['custom']['source'];
			if (file_exists($checker_file))
			{
				$hash_code.=sha1_file($checker_file,true);
			}
			else
			{
				$verify_result['checker'] = false;
				$verify_result['overall'] = 'checker';
			}
		}
		
		$success = true;
		$case_id = 0;
		foreach($data_config['case'] as $item)
		{
			$input_file = $verify_result['testdata_path'].$item['input'];
			$output_file = $verify_result['testdata_path'].$item['output'];
			
			if (file_exists($input_file))
			{
				$verify_result['case'][$case_id]['input'] = true;
				$hash_code.=sha1_file($input_file,true);
			}
			else
			{
				$verify_result['case'][$case_id]['input'] = false;
				$success = false;
			}
			
			if (file_exists($output_file))
			{
				$verify_result['case'][$case_id]['output'] = true;
				$hash_code.=sha1_file($output_file,true);
			}
			else
			{
				$verify_result['case'][$case_id]['output'] = false;
				$success = false;
			}
			
			++$case_id;
		}
		$hash_code = sha1($hash_code);

		if (!$success)
		{
			$verify_result['overall'] = 'case';
		}
		else
		{
			$verify_result['hash_code'] = $hash_code;
			$problem_meta = new MDL_Problem_Meta($data_config['id']);
			$problem_meta->setVar('verified',1);
		}
		
		return $verify_result;
	}
	
	public static function getJudgersTestdataVersion($data_config)
	{
		$judgers = MDL_Judger_Detail::getJudgers();
		foreach($judgers as $key=>$item)
		{
			$judger_url = $judgers[$key]['judger_config']['url'];
			$public_key = $judgers[$key]['judger_config']['public_key'];
			$rs = MDL_Judger_Data::getTestdataVersion($judger_url,$public_key,$data_config['name']);
			$judgers[$key] = array_merge($judgers[$key],$rs);
		}
		return $judgers;
	}
	
	public static function transmitTestdata($judger_id,$data_config)
	{
		$judger = MDL_Judger_Detail::getJudger($judger_id);
		//Upload testdata files
		MDL_Judger_Transmit::sendTestdata($judger['judger_config'],$data_config);
		return MDL_Judger_Data::updateTestdata($judger['judger_config']['url'],$data_config['name']);
	}
}