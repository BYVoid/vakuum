<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Problem extends MDL_Exception
{
	const FIELD_PROBLEM = "problem";
	const INVALID_PROB_ID = "invalid_prob_id";
	const UNVALIDATED_PROBLEM = "unvalidated_problem";
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_PROBLEM] = $message;
		parent :: __construct(self::FIELD_PROBLEM);
	}
}