<?php

abstract class BFL_RemoteAccess_Common
{
	protected function encode($data)
	{
		return base64_encode(gzdeflate(serialize($data)));
	}
	
	protected function decode($str)
	{
		return unserialize(gzinflate(base64_decode($str)));
	}
	
	public static function keyhash($key)
	{
		return md5(sha1($key,true).$key);
	}

	protected function __fetch($url,$request)
	{
		$request=array('BFL_RemoteAccess' => $request);
		if (function_exists('curl_exec'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			$rs = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$query = http_build_query($request);
			$opts = array
			(
				'http'=>array
				(
					'method'=>"POST",
					'header'=>'Content-type: application/x-www-form-urlencoded; charset=utf-8'."\r\n". 'Content-length: '.strlen($query),
					'content'=> $query
				)
			);
			$context = stream_context_create($opts);
			$rs = file_get_contents($url, false, $context);
		}
		return $rs;
	}
	
	protected function __execute($url,$request,$block)
	{
		$path = $host = $port = '';
		$this->getAddress($url,$host,$port,$path);
		$fp = @fsockopen($host, $port, $errno, $errstr, 6);
		if(!$fp)
			return false;
		$query = http_build_query(array('BFL_RemoteAccess' => $request));
		$out = 'POST ' . $path . ' HTTP/1.1' . "\n"
			 . 'Host: ' . $host . "\n"
			 . 'Connection: close' . "\n"
			 . 'Content-Length: ' . strlen($query) . "\n"
			 . 'Content-Type: application/x-www-form-urlencoded; charset=utf-8' . "\n\n"
			 . $query . "\n";
		fwrite($fp, $out);
		if ($block)
		{
			$out = '';
			while (!feof($fp))
				$out .= fread($fp,128);
			return $out;
		}
		fclose($fp);
		return true;
	}
	
	private function getAddress($url,&$host,&$port,&$path)
	{
		$url = str_replace('http://', '', $url);
		$path = explode('/', $url);
		$url = explode(':',$path[0]);
		unset($path[0]);
		$path = '/' . implode('/', $path);
		$host = $url[0];
		if (isset($url[1]))
			$port = $url[1];
		else
			$port = 80;
	}
}

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

class BFL_RemoteAccess_Client extends BFL_RemoteAccess_Common
{
	private $server_url,$server_key,$defaultType;
	public function __construct($url,$key,$type=0)
	{
		$this->server_url = $url;
		$this->server_key = $key;
		$this->defaultType = $type;
	}
	
	public function __setCallType($type)
	{
		$this->defaultType = $type;
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
					trigger_error($str,E_USER_WARNING);
				}
				else
				{
					trigger_error($result['fatal'],E_USER_WARNING);
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
