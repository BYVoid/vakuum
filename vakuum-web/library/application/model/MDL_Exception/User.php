<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception_User extends MDL_Exception
{
	const FIELD_USER = "user";
	const INVALID_USER_ID = "invalid_user_id";
	const INVALID_USER_NAME = "invalid_user_name";
	const INVALID_USER_PASSWORD = "invalid_user_password";
	const INVALID_USER_PASSWORD_REPEAT = "invalid_user_password_repeat";
	const INVALID_NICKNAME = "invalid_nickname";
	const INVALID_EMAIL = "invalid_email";
	const INVALID_WEBSITE = "invalid_website";
	const INVALID_MEMO = "invalid_memo";
	
	const UNVALIDATED_USER = "unvalidated_user";
	const USER_NAME_OCCUPIED = 'user_name_occupied';
	
	public function __construct($message)
	{
		$this->desc[self::FIELD_USER] = $message;
		parent :: __construct(self::FIELD_USER);
	}
}