<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Judge_FTP extends MDL_Exception_Judge
{
	const FIELD_JUDGE_FTP = "judge_ftp";
	const CONNECT = "connect";
	const LOGIN = "login";
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_JUDGE_FTP] = $message;
		parent :: __construct(self::FIELD_JUDGE_FTP);
	}
}