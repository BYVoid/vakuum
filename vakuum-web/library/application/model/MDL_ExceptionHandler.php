<?php
class MDL_ExceptionHandler
{
	/**
	 * 
	 * @param MDL_Exception $exception
	 */
	public static function exceptionHandler($exception)
	{
		$act = '';
		//if ($exception->testTopDesc(MDL_Exception::ERROR_NOTFOUND))
		{
			$act = BFL_Serializer::transmitEncode($exception->getDesc());
		}
		if ($act == '')
			throw $exception;
		MDL_Locator::getInstance()->redirect('error', NULL, '?'.$act);
	}
}