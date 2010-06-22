<?php
require_once('Common.php');

/**
 * User Authentication Model Class
 *
 * @package User Authentication
 */
class MDL_User_Verify extends MDL_User_Common
{
	public static function getRestrict()
	{
		$config = MDL_Config::getInstance();
		$restrict = unserialize($config->getVar('register_form_restrict'));
		return $restrict;
	}
	
	public static function checkUserName($user_name)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select count(*) as `exist` from '.DB_TABLE_USER.' where `user_name`=:user_name');
		$stmt->bindParam(':user_name', $user_name);
		$stmt->execute();
		$rs = $stmt->fetch();
		return $rs['exist'] == 0;
	}

	public static function verify(&$user_info,$password_verify=true)
	{
		$restrict = self::getRestrict();
		
		//User_name verificaion
		$re = '/^\w{'. $restrict['user_name']['length_min'] .','. $restrict['user_name']['length_max'] .'}$/';
		if (! preg_match($re,$user_info['user_name']))
			throw new MDL_Exception_User_Passport('user_name');
		
		//User_password verificaion
		if ($password_verify)
		{
			$re = '/^\w{'. $restrict['user_password']['length_min'] .','. $restrict['user_password']['length_max'] .'}$/';
			if (! preg_match($re,$user_info['user_password']))
				throw new MDL_Exception_User_Passport('user_password');
			//Password consistency verificaion
			if ($user_info['user_password'] != $user_info['user_password_repeat'])
				throw new MDL_Exception_User_Passport('user_password_repeat');
		}

		//Nickname verificaion
		$re = '/^.{'. $restrict['nickname']['length_min'] .','. $restrict['nickname']['length_max'] .'}$/';
		if (! preg_match($re,$user_info['user_nickname']))
			throw new MDL_Exception_User_Passport('user_nickname');
			
		//Email verificaion
		if (strlen($user_info['email']) > $restrict['email']['length_max'] ||
			! preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$user_info['email']))
			throw new MDL_Exception_User_Passport('email');
		
		//Website verfication
		if (strlen($user_info['website']) > $restrict['website']['length_max'])
			throw new MDL_Exception_User_Passport('website');
			
		//Memo verfication
		if (strlen($user_info['memo']) > $restrict['memo']['length_max'])
			throw new MDL_Exception_User_Passport('memo');
	}
}