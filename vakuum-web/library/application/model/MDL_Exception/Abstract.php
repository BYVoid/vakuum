<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
abstract class MDL_Exception_Abstract extends RuntimeException
{
	protected $desc=array();
	public function getDescription()
	{
		return $this->desc;
	}
	
	public function __construct()
	{
		reset($this->desc);
		parent :: __construct(current($this->desc));
	}
}