<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Judge extends MDL_Exception
{
	const FIELD_JUDGE = "judge";
	const INVALID_SOURCE_ENCODIND = "invalid_source_encoding";
	const INVALID_SOURCE_LENGTH = "invalid_source_length";
	const INVALID_UPLOAD_METHOD = "invalid_upload_method";
	const TASK_UPLOAD = "task_upload";
	const TESTDATA_UPLOAD = "testdata_upload";
	const ALREADY_RUNNING = "already_running";

	public function __construct($message)
	{
		$this->desc[self::FIELD_JUDGE] = $message;
		parent :: __construct(self::FIELD_JUDGE);
	}
}