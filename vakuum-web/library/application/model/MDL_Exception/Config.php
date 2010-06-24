<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Config extends MDL_Exception
{
	const FIELD_CONFIG = "config";
	const INVALID_PAGE_SIZE = "invalid_page_size";
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_CONFIG] = $message;
		parent :: __construct(self::FIELD_CONFIG);
	}
}