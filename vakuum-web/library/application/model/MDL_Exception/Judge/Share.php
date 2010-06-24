<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_Judge_Share extends MDL_Exception_Judge
{
	const FIELD_JUDGE_SHARE = "judge_share";
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_JUDGE_SHARE] = $message;
		parent :: __construct(self::FIELD_JUDGE_SHARE);
	}
}