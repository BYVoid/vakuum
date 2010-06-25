<?php
class MDL_GlobalControl
{
	private static $error_page = "error";
	
	public static function setErrorPage($error_page)
	{
		self::$error_page = $error_page;
	}
	
	public static function shutdownHandler()
	{
		//提交數據庫改動
		$db = BFL_Database::getInstance();
		$db->commit();
	}
	
	/**
	 * 
	 * @param Exception $exception
	 */
	public static function exceptionHandler($exception)
	{
		//撤銷數據庫改動
		$db = BFL_Database::getInstance();
		$db->rollback();
		
		if ($exception instanceof MDL_Exception)
		{
			$desc = BFL_Serializer::transmitEncode($exception->getDesc());
			MDL_Locator::getInstance()->redirect(self::$error_page, NULL, '?'.$desc);
		}
		else 
		{
			if (DEBUG)
			{
				echo $exception->getMessage();
				$error_massage = $exception->getTraceAsString();
			}
			else
			{
				if ($exception instanceof PDOException)
					$error_massage = "DB Error";
				else
					$error_massage = "Vakuum Error";
			}
			die($error_massage);
		}
	}
}