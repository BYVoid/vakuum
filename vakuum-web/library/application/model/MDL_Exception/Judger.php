<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Judger extends MDL_Exception
{
	const FIELD_JUDGER = "judger";
	const INVALID_INITIAL_GET = "invalid_initial_get";

	public function __construct($message)
	{
		$this->desc[self::FIELD_JUDGER] = $message;
		parent :: __construct(self::FIELD_JUDGER);
	}
}