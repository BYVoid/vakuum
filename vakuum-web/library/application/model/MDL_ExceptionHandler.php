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
		//撤銷數據庫改動
		$db = BFL_Database::getInstance();
		$db->rollback();
		
		if ($exception instanceof PDOException)
		{
			if (DEBUG)
			{
				echo $exception->getMessage();
				$error_massage = $exception->getTraceAsString();
			}
			else
			{
				$error_massage = "DB Error";
			}
			die($error_massage);
		}
		
		$desc = BFL_Serializer::transmitEncode($exception->getDesc());
		MDL_Locator::getInstance()->redirect(self::$error_page, NULL, '?'.$desc);
	}
}