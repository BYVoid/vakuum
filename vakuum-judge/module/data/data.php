<?php
function updateTestdata($prob_name)
{
	$path = Config::getInstance()->getVar('path');
	$testdata_path = $path['testdata'] . $prob_name . '/';
	
	$rs = getTestdataVersion($prob_name);
	if ($rs['version'] != 0)
	{
		$xml = file_get_contents($testdata_path.'config.xml');
		$data_config = BFL_XML::XML2Array($xml);
		if ($data_config['checker']['type'] == 'custom' && $data_config['checker']['custom']['language']!= '')
		{
			//compile checker
			chdir($testdata_path);
			$compiler_path = $path['compiler'];
			$language = $data_config['checker']['custom']['language'];
			$source_file = $data_config['checker']['custom']['source'];
			$binary_file = $data_config['checker']['name'];
			$command = "{$compiler_path}compiler {$compiler_path}config.ini {$language} {$source_file} {$binary_file}";
			list($result,$option) = exec($command);
			
			$rs = getTestdataVersion($prob_name);
			
			if ($result == 0)
				$rs['checker_compile'] = 1;
			if (file_exists('compile_message.txt'))
				unlink('compile_message.txt');
		}
	}
	
	return $rs;
}

function getTestdataVersion($prob_name)
{
	$path = Config::getInstance()->getVar('path');
	$testdata_path = $path['testdata'] . $prob_name . '/';
	$rs['version'] = 0;
	$rs['hash_code'] = '';
	
	if (file_exists($testdata_path.'config.xml'))
	{
		$xml = file_get_contents($testdata_path.'config.xml');
		$data_config = BFL_XML::XML2Array($xml);
		
		if (!isset($data_config['version']) || $data_config['version'] == 0)
			$rs['version'] = 0;
		else
			$rs['version'] = $data_config['version'];
			
		if ($data_config['checker']['type'] == 'custom')
		{
			if (!file_exists($testdata_path.$data_config['checker']['name']))
				$rs['version'] = -1;
		}
		
		$hash_code='';
		
		if ($data_config['checker']['type'] == 'custom')
		{
			$checker_file = $testdata_path.$data_config['checker']['custom']['source'];
			if (file_exists($checker_file))
				$hash_code.=sha1_file($checker_file,true);
		}
		
		foreach($data_config['case'] as $item)
		{
			$input_file = $testdata_path.$item['input'];
			$output_file = $testdata_path.$item['output'];
			
			if (file_exists($input_file))
				$hash_code.=sha1_file($input_file,true);
			
			if (file_exists($output_file))
				$hash_code.=sha1_file($output_file,true);
		}
		$hash_code = sha1($hash_code);
		$rs['hash_code'] = $hash_code;
	}
	
	return $rs;
}