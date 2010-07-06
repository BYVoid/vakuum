<?php
function creatlink($src,$dest)
{
	if (is_link($dest))
		unlink($dest);
	if (symlink($src,$dest) === false)
		writelog('Symbol link failed to create from '.$src.' to '.$dest);
}

function readTestdataConfig($file_path)
{
	$xml = file_get_contents($file_path);
	$_array = BFL_XML::XML2Array($xml);
	return $_array;
}

function writeExecutorConfigFile($program,$time_limit,$memory_limit,$output_limit,$openfile)
{
	$prob_config = array
	(
		'program' => $program,
		'file' => $openfile,
	);
	if ($time_limit != 0)
		$prob_config['time_limit'] = $time_limit;
	if ($memory_limit != 0)
		$prob_config['memory_limit'] = $memory_limit;
	if ($output_limit != 0)
		$prob_config['output_limit'] = $output_limit;

	$xml = BFL_XML::Array2XML($prob_config);
	file_put_contents("prob.xml",$xml);
}


$prob_name = $this->prob_name;
$binary_file = $this->binary_file;
writelog('Run...');
//Get paths
$path = Config::getInstance()->getVar('path');
$executor_path = $path['executor'];
$checker_path = $path['checker'];
$testdata_path = $path['testdata'] . $prob_name . '/';
if (DEBUG_MODE)
	$executor = $executor_path.'executor_dev';
else
	$executor = $executor_path.'executor';

//Fetch testdata settings
if (!file_exists($testdata_path.'config.xml'))
	return 'testdata';
$testdata = readTestdataConfig($testdata_path.'config.xml');
$file_input = $testdata['input'];
$file_output = $testdata['output'];
$file_answer = 'stdans';

$checker = $testdata['checker']['name'];
if ($testdata['checker']['type'] == 'custom')
	$checker = $testdata_path.$checker;
else
	$checker = $checker_path.$checker;

if (!file_exists($checker))
	return 'checker';

//Initialize varibles
$case_id = $total_time = $max_memory = $total_score = 0;
$fatal = 0;
$openfile = array($file_input,$file_output);
if (isset($testdata['additional_file']))
{
	if (!is_array($testdata['additional_file']))
		$openfile[] = $testdata['additional_file'];
	else if (!empty($testdata['additional_file']))
		$openfile = array_merge($openfile,$testdata['additional_file']);
}

foreach($testdata['case'] as $case)
{
	++$case_id;
	writelog('#'.$case_id);
	$time_limit = $memory_limit = $output_limit = 0;
	//Read limits from general
	if (isset($testdata['time_limit']))
		$time_limit = $testdata['time_limit'];
	if (isset($testdata['memory_limit']))
		$memory_limit = $testdata['memory_limit'];
	if (isset($testdata['output_limit']))
		$output_limit = $testdata['output_limit'];
	//Read limits specified by case
	if (isset($case['time_limit']))
		$time_limit = $case['time_limit'];
	if (isset($case['memory_limit']))
		$memory_limit = $case['memory_limit'];
	if (isset($case['output_limit']))
		$output_limit = $case['output_limit'];

	if (!file_exists($testdata_path.$case['input']))
		return 'testdata';
	creatlink("{$testdata_path}{$case['input']}",$file_input);

	//Create configure file for executor
	writeExecutorConfigFile($binary_file,$time_limit,$memory_limit,$output_limit,$openfile);

	$this->execute("{$executor} prob.xml",$stdout,$stderr);
	list($result,$option,$time_used,$memory_used) = explode(" ",$stdout);

	$total_time += $time_used;
	if ($memory_used > $max_memory)
		$max_memory = $memory_used;

	$post_result = array
	(
		'type' => 'execute',
		'info' => array
		(
			'case_id' => $case_id,
			'result' => $result,
			'option' => $option,
			'time_used' => $time_used,
			'memory_used' => $memory_used,
			'score' => 0,
			'check_message' => '',
		)
	);

	if ($result == 0)
	{
		//Run successfully
		if (!file_exists($testdata_path.$case['output']))
			return 'testdata';

		creatlink("{$testdata_path}{$case['output']}",$file_answer);
		$this->execute("{$checker} {$file_input} {$file_output} {$file_answer}",$stdout,$stderr);

		list($score,$message) = explode("\n",$stdout);
		$post_result['info']['score'] = $score;
		$post_result['info']['check_message'] = $message;
		$total_score += $score;

		if ($fatal == 0 && $score != 1.00)
		{
			//Wrong Answer
			$fatal = self::RESULT_WRONG_ANSWER;
		}
	}
	else
	{
		if ($fatal == 0)
		{
			$fatal = $result;
		}
	}
	if (file_exists('executor.log'))
	{
		rename('executor.log', 'executor_'.$case_id.'.log');
	}
	if (file_exists($file_output))
	{
		rename($file_output, $file_output.$case_id);
	}
	$this->sendBack($post_result);
}

$summary = array
(
	'time' => $total_time,
	'memory' => $max_memory,
	'fatal' => $fatal,
	'score' => ($total_score / $case_id),
);

writelog("Run Summary");
writelog($summary);