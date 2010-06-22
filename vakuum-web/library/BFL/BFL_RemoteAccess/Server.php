<?php

class BFL_RemoteAccess_Server extends BFL_RemoteAccess_Common
{
	private $key;
	private $user_functions;

	public function __construct($key)
	{
		$this->key = $this->keyhash($key);
		$this->user_functions = array();
	}
	
	public function bindFunction($name,$func)
	{
		$this->user_functions[$name] = $func;
	}
	
	private function call($action,$arguments)
	{
		if (isset($this->user_functions[$action]))
		{
			set_error_handler(array($this,'myErrorHandler'));
			$rs = call_user_func_array($this->user_functions[$action],$arguments);
			$this->result['response'] = $rs;
		}
		else
		{
			$this->result['fatal'] = 'function undefined';
		}
	}
	
	public function listen()
	{
		if (!isset($_POST['BFL_RemoteAccess']))
			return false;
		$request = $this->decode($_POST['BFL_RemoteAccess']);
		$response = array
		(
			'status' => 'success',
		);
		if ($request['key'] == $this->key)
		{
			$this->call($request['action'],$request['arguments']);
			$result = $this->result;
			if (isset($result['fatal']))
			{
				$response['status'] = 'fatal';
				$response['fatal'] = $result['fatal'];
			}
			else
			{
				$response['response'] = $result['response'];
			}
		}
		else
		{
			$response['status'] = 'fatal';
			$response['fatal'] = 'invalid key';
		}
		$response = $this->encode($response);
		echo $response;
		return true;
	}
	
	public function myErrorHandler($errno, $errstr, $errfile, $errline)
	{
		$this->result['fatal'] = array($errno,$errstr,$errfile,$errline);
		return true;
	}
}

