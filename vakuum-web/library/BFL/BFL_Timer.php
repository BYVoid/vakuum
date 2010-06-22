<?php
class BFL_Timer
{
	protected static $time_start;
	
	public static function initialize()
	{
		self :: $time_start = self :: getMicroTime();
	}
	
	public static function getScriptExecutingTime()
	{
		return self :: getMicroTime() - self :: $time_start;
	}
	
	public static function getMicroTime()
	{
		$mt = explode(' ',microtime());
		return $mt[1] + $mt[0];
	}
	
}