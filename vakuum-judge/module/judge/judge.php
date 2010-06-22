<?php
require('common.php');

function judge($task)
{
	writelog($task);
	$path = Config::getInstance()->getVar('path');
	$original_cd = getcwd();
	chdir($path['task']. $task['task_name']);
	
	$judge = new Judge($task['task_name'],$task['prob_name'],$task['source_file'],$task['language'],$task['return_url'],$task['public_key']);
	
	try
	{
		$result = $judge->compile();
		if ($result == 0)
		{
			$info = $judge->run();
			if (!is_array($info))
			{
				writelog('ERROR: '.$info); 
				switch ($info)
				{
					case 'testdata':
					case 'checker':
					default:
						//Run failed
						$info = array
						(
							'fatal' => Judge::RESULT_EXECUTOR_ERROR,
							'time' => 0,
							'memory' => 0,
							'score' => 0.00,
						);
				} 
			}
		}
		else
		{
			//Compile failed
			$info = array
			(
				'fatal' => Judge::RESULT_COMILATION_ERROR,
				'time' => 0,
				'memory' => 0,
				'score' => 0.00,
			);
		}
		$judge->complete($info);
	}
	catch(Exception $e)
	{
		writelog("Stopped");
	}
	
	$judge->clear();
	
	chdir($original_cd);
}

function stopJudge($task_name)
{
	$path = Config::getInstance()->getVar('path');
	if (file_exists($path['task'].$task_name))
	{
		file_put_contents($path['task']. $task_name.'/stop.action','');
	}
}
