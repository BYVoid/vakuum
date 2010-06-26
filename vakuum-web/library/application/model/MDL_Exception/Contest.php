<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Contest extends MDL_Exception
{
	const FIELD_CONTEST = "contest";
	const INVALID_CONTEST_ID = "invalid_contest_id";
	const SIGN_UP_ALREADY = "sign_up_already";
	const NOT_SIGN_UP = "not_sign_up";
	const NOT_DURING_SIGN_UP_TIME = "not_during_sign_up_time";
	const NOT_DURING_CONTEST_TIME = "not_during_contest_time";
	const INVALID_PROB_ALIAS = "invalid_prob_alias";

	public function __construct($message)
	{
		$this->desc[self::FIELD_CONTEST] = $message;
		parent :: __construct(self::FIELD_CONTEST);
	}
}