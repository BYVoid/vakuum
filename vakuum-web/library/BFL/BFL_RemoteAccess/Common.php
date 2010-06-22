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
