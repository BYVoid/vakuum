<?php
class MDL_ExceptionHandler
{
	private static $error_page = "error";
	
	public static function setErrorPage($error_page)
	{
		self::$error_page = $error_page;
	}
	
	/**
	 * 
	 * @param MDL_Exception $exception
	 */
	public static function exceptionHandler($exception)
	{
		$desc = BFL_Serializer::transmitEncode($exception->getDesc());
		MDL_Locator::getInstance()->redirect(self::$error_page, NULL, '?'.$desc);
	}
}