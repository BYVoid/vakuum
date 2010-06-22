<?php
/**
 * Post requests via HTTP
 * @version 20100326
 * @author BYVoid
 */
class BFL_HTTP
{
	/**
	 * Get host server name and request path
	 * @param string $url
	 * @param strin> $path
	 */
	private static function getAddress($url,&$host,&$port,&$path)
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

	/**
	 * Send Request
	 * @param string $url Query Address
	 * @param array $request Request Array
	 * @return boolean whether request succeeded sent
	 */
	public static function execute($url,$block = false,$request = array())
	{
		$path = $host = $port = '';
		self::getAddress($url,$host,$port,$path);
		$fp = @fsockopen($host, $port, $errno, $errstr, 6);
		if(!$fp)
			return false;
		$query = htmlspecialchars_decode(http_build_query($request));
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

	/**
	 * Send Request and fetch result
	 * @param string $url Query Address
	 * @param array $request Request Array
	 * @return string result
	 */
	public static function fetch($url,$request=array())
	{
		if (function_exists('curl_exec'))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			$rs = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$query = htmlspecialchars_decode(http_build_query($request));
			$opts = array
			(
				'http'=>array
				(
					'method'=>"POST",
					'header'=>'Content-type: application/x-www-form-urlencoded; charset=utf-8'."\r\n". 'Content-length: '.strlen($query),
					'content'=>$query
				)
			);
			$context = stream_context_create($opts);
			$rs = file_get_contents($url, false, $context);
		}
		return $rs;
	}
}