<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Problem_Edit extends MDL_Exception_Problem
{
	public function __construct($message)
	{
		array_unshift($this->desc,$message);
		parent :: __construct('edit');
	}
}