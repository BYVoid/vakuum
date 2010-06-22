<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Judge_Submit extends MDL_Exception_Judge
{
	public function __construct($message)
	{
		array_unshift($this->desc,$message);
		parent :: __construct('submit');
	}
}