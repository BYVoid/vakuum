<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_User_Passport extends MDL_Exception_User
{
	public function __construct($message)
	{
		array_unshift($this->desc,$message);
		parent :: __construct('passport');
	}
}