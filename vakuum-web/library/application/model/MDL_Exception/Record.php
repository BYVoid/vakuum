<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Record extends MDL_Exception
{
	const FIELD_RECORD = "record";
	const INVALID_RECORD_ID = "invalid_record_id";
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_RECORD] = $message;
		parent :: __construct(self::FIELD_RECORD);
	}
}