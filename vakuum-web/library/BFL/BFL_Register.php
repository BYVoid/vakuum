<?php
class BFL_Register
{
	protected static $_array;
	
	public static function setVar($key,$val)
	{
		self::$_array[$key] = $val;
	}
	
	public static function getVar($key)
	{
		if (!isset(self::$_array[$key]))
			self::$_array[$key] = '';
		return self::$_array[$key];
	}
	
	public static function getAll()
	{
		return self::$_array;
	}
	
	public static function haveVar($key)
	{
		return isset(self::$_array[$key]);
	}
	
	public static function unsetVar($key)
	{
		if (isset(self::$_array[$key]))
			unset(self::$_array[$key]);
	}
}