<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Config extends MDL_Exception
{
	public function __construct($message)
	{
		array_unshift($this->desc,$message);
		parent :: __construct('config');
	}
}