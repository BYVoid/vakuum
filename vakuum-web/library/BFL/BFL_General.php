<?php
class BFL_General
{
	public static function getServerName()
	{
		if (!empty($_SERVER['HTTP_HOST']))
			$server_name = $_SERVER['HTTP_HOST'];
		else if ($_SERVER['SERVER_PORT'] == 80)
			$server_name =  $_SERVER['SERVER_NAME'];
		else
			$server_name = $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		return strtolower($server_name);
	}
	
	public static function getServerAddress()
	{
		$protocal = 'http://';
		return $protocal.self::getServerName();
	}

	public static function arrayMerge($def,$cus)
	{
		if (!is_array($def))
			$def = array($def);
		if (!is_array($cus))
			$cus = array($cus);
		foreach ($cus as $key=>$value)
		{
			if (is_array($value) && isset($def[$key]))
				$def[$key] = self::arrayMerge($def[$key],$value);
			else
				$def[$key] = $value;
		}
		return $def;
	}
}