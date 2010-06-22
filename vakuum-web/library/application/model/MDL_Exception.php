<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception extends MDL_Exception_Abstract
{
	public function __construct($message)
	{
		array_unshift($this->desc,$message);
		parent :: __construct();
	}
}