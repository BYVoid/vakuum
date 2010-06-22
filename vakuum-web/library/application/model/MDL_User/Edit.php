<?php
require_once('Common.php');

class MDL_User_Edit extends MDL_User_Common
{
	public static function create($user)
	{
		if (!MDL_User_Verify::checkUserName($user['user_name']))
			throw new MDL_Exception_User_Passport('user_name_occupied');
		MDL_User_Verify::verify($user);
		
		$config = MDL_Config::getInstance();
		$default_identity = 'general';
		
		$user_password_hash = self::passwordEncrypt($user['user_password']);
		
		$db = BFL_Database :: getInstance();
		$meta = array(
			'user_name' => ':user_name' ,
			'user_nickname' => ':user_nickname' ,
			'user_password' => ':user_password' );
		$stmt = $db->insert(DB_TABLE_USER , $meta);
		$stmt->bindParam(':user_name', $user['user_name']);
		$stmt->bindParam(':user_nickname', $user['user_nickname']);
		$stmt->bindParam(':user_password', $user_password_hash);
		$stmt->execute();
		$user_id = $db->getLastInsertID();
		
		$register_time = time();
		
		$user_meta = new MDL_User_Meta($user_id);
		$meta = array(
			'email' => $user['email'],
			'website' => $user['website'],
			'memo' => $user['memo'],
			'register_time' => $register_time,
			'identity' => $default_identity,
		);
		
		if ($config->getVar('register_email_validate') == 1)
		{
			$meta['identity'] = 'unvalidated';
			$meta['validation_code'] = self::getValidationCode($user['user_name'],$register_time);
			self::sendValidationCode($user['user_name'],$user['email'],$meta['validation_code']);
		}
		
		$user_meta->setMetas($meta);
	}
	
	private static function getValidationCode($user_name,$register_time)
	{
		return self::passwordEncrypt($user_name.$register_time);
	}
	
	public static function sendValidationCode($user_name,$email,$validation_code)
	{
		$server_name = BFL_General::getServerName();
		$site_address = BFL_General::getServerAddress();
		$options = array
		(
			'user_name'=>$user_name,
			'code'=>$validation_code,
		);
		$validation_address = MDL_Locator::getInstance()->getURL('passport_dovalidation',$options);
		
		if (strpos($validation_address,$site_address) !== 0)
		{
			$validation_address = $site_address . $validation_address;
		}
		
		$site_name = MDL_Config::getInstance()->getVar('site_name');
		
		$view = MDL_View::getInstance();
		$view->validation = array
		(
			'user_name' => $user_name,
			'site_name' => $site_name,
			'site_address' => $site_address,
			'validation_address' => $validation_address,
		);
		
		$message = $view->render('text/email_validation.php');
		$mail_sender = new BFL_Mail($email,$site_name,$message);
		$mail_sender->setFrom($site_name . " <vakuum@{$server_name}>");
		return $mail_sender->send();
	}
	
	public static function edit($user,$reauth = true)
	{
		$meta = array('`user_nickname` = :user_nickname');
		
		if ($user['user_password'] != '')
		{
			if ($reauth)
			{
				//重新验证密码
				$original_hash=self::passwordEncrypt($user['user_password_original']);
				if ($original_hash != $user['user_password_correct'])
					throw new MDL_Exception_User_Passport('user_password_original');
			}
			$meta[] = '`user_password` = :user_password';
			$edit_password = true;
		}
		else
			$edit_password = false;
		
		MDL_User_Verify::verify($user,$edit_password);
		
		$user_password_hash = self::passwordEncrypt($user['user_password']);
		
		$user_id = $user['user_id'];
		
		$db = BFL_Database :: getInstance();
		$stmt = $db->update(DB_TABLE_USER,$meta,'where `user_id`=:user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':user_nickname', $user['user_nickname']);
		if ($edit_password)
			$stmt->bindParam(':user_password', $user_password_hash);
		$stmt->execute();
		
		$user_meta = new MDL_User_Meta($user_id);
		$meta = array
		(
			'email' => $user['email'],
			'website' => $user['website'],
			'memo' => $user['memo'],
			'identity' => $user['identity'],
		);
		$user_meta->setMetas($meta);
	}
	
	public static function remove($user_id)
	{
		$db = BFL_Database::getInstance();
		$db->beginTransaction();
		
		$stmt = $db->delete(DB_TABLE_USER,'where `user_id`=:user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		
		$stmt = $db->delete(DB_TABLE_USERMETA,'where `umeta_user_id`=:user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		
		
		$stmt = $db->factory('select `record_id` from '.DB_TABLE_RECORD.' where `record_user_id`=:user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$records = $stmt->fetchAll();
		$stmt = NULL;
		foreach($records as $record)
		{
			MDL_Record_Edit::delete($record['record_id']);
		}
		
		$db->commit();
	}
	
	public static function validate($user_name,$validation_code)
	{
		$user = MDL_User_Detail::getUserByName($user_name);
		
		if ($user['identity'] != 'unvalidated' || !isset($user['validation_code']))
			throw new MDL_Exception_User('no validation');
		
		if ($user['validation_code'] == $validation_code)
		{
			$user_meta = new MDL_User_Meta($user['user_id']);
			$user_meta->setVar('identity','general');
			$user_meta->unsetVar('validation_code');
			
			MDL_User_Auth::logout();
			BFL_ACL::getInstance()->setUserID($user['user_id']);
		}
	}
}