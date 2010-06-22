<?php
class Judge
{
	const RESULT_ACCEPTED = 0;
	const RESULT_RUNTIME_ERROR = 1;
	const RESULT_TIME_LIMIT_EXCEED = 2;
	const RESULT_MEMORY_LIMIT_EXCEED = 3;
	const RESULT_OUTPUT_LIMIT_EXCEED = 4;
	const RESULT_SYSCALL_RESTRICTED = 5;
	const RESULT_EXECUTOR_ERROR = 6;
	const RESULT_WRONG_ANSWER = 7;
	const RESULT_COMILATION_ERROR = 8;
	private $task_name,$prob_name,$source_file,$binary_file,$language,$return_url,$public_key;
	
	public function __construct($task_name,$prob_name,$source_file,$language,$return_url,$public_key)
	{
		$this->task_name = $task_name;
		$this->prob_name = $prob_name;
		$this->source_file = $source_file;
		$this->binary_file = $task_name.'.vjb';
		$this->language = $language;
		$this->return_url = $return_url;
		$this->public_key = $public_key;
	}
	
	public function compile()
	{
		require('compile.php');
		return $result;
	}
	
	public function run()
	{
		require('run.php');
		return $summary;
	}
	
	public function complete($info)
	{
		$post_result = array
		(
			'type' => 'complete',
			'info' => $info,
		);
		$this->sendBack($post_result);
	}
	
	private function checkStop()
	{
		return file_exists('stop.action');
	}
	
	private function fileread($fp)
	{
		$str = '';
		if (is_resource($fp))
		{
			while (!feof($fp))
				$str .= fgets($fp, 128);
		}
		return $str;
	}
	
	private function execute($command,&$stdout,&$stderr)
	{
		writelog("EXECUTE:".$command);
		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
		);
		$process = proc_open($command, $descriptorspec, $pipes);
		if (is_resource($process))
		{
			$fpout = $pipes[1];
			$fperr = $pipes[2];
	
			$stdout = $this->fileread($fpout);
			$stderr = $this->fileread($fperr);
			
			fclose($fpout);
			fclose($fperr);
			proc_close($process);
		}
	}
	
	private function sendBack($post_result)
	{
		if ($this->checkStop())
			throw new Exception('stop');
		$return_url = $this->return_url;
		$public_key = $this->public_key;
		writelog('Sending back to '.$return_url);
		writelog($post_result);
		$client = new BFL_RemoteAccess_Client($return_url,$public_key,0);
		$client->writeRecord($post_result);
	}
	
	function clear()
	{
		//if (!DEBUG_MODE)
		{
			$dirname = $this->task_name;
			chdir('..');
			exec("rm -rf {$dirname}");
		}
	}
}
