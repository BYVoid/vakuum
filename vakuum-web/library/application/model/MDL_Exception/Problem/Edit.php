<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Problem_Edit extends MDL_Exception_Problem
{
	const FIELD_PROBLEM_EDIT = "field_problem_edit";
	const INVALID_PROB_ID = 'invalid_prob_id';
	const INVALID_PROB_NAME = 'invalid_prob_name';
	const INVALID_PROB_TITLE = 'invalid_prob_title';
	const INVALID_DATA_CASE = 'invalid_data_case';
	const INVALID_DATA_INPUT = 'invalid_data_input';
	const INVALID_DATA_OUTPUT = 'invalid_data_output';
	const INVALID_DATA_CHECKER = 'invalid_data_checker';
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_PROBLEM_EDIT] = $message;
		parent :: __construct(self::FIELD_PROBLEM_EDIT);
	}
}