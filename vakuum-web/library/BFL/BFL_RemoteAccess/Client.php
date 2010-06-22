<?php

class BFL_RemoteAccess_Client extends BFL_RemoteAccess_Common
{
	private $server_url,$server_key,$defaultType;
	private $throw_exception;
	
	public function __construct($url,$key,$type=0)
	{
		$this->server_url = $url;
		$this->server_key = $key;
		$this->defaultType = $type;
		$this->throw_exception = false;
	}
	
	public function __setCallType($type)
	{
		$this->defaultType = $type;
	}
	
	public function __throwException($bool)
	{
		$this->throw_exception = $bool;
	}
	
	private function __remoteCall($action,$arguments,$type)
	{
		$request = array
		(
			'key' => $this->server_key,
			'action' => $action,
			'arguments' => $arguments,
		);
		$request = $this->encode($request);
		if ($type == 0)
		{
			$result = $this->__fetch($this->server_url,$request);
			$result = $this->decode($result);
			if ($result['status'] == 'success')
			{
				return $result['response'];
			}
			else
			{
				if (is_array($result['fatal']))
				{
					$str = '"'.$result['fatal'][1] . ' in ' .$result['fatal'][2] . ' on line #'. $result['fatal'][3].'"';
					
				}
				else
				{
					$str = $result['fatal'];
				}
				
				if ($this->throw_exception)
				{
					throw new Exception($str);
				}
				else
				{
					trigger_error($str,E_USER_WARNING);
				}
			}
		}
		else if ($type == 1)
		{
			return $this->__execute($this->server_url,$request,false);
		}
		else
		{
			return $this->__execute($this->server_url,$request,true);
		}
	}
	
	public function __call($action,$arguments)
	{
		return $this->__remoteCall($action,$arguments,$this->defaultType);
	}
}
