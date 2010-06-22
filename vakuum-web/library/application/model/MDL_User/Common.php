<?php
/**
 * User Authentication Model Class
 *
 * @package User Authentication
 */
abstract class MDL_User_Common
{
	protected static function passwordEncrypt($password)
	{
		return md5(sha1($password,true) . BFL_Register :: getVar('password_encode_word'));
	}
	
	protected static function getMetaSQL($umeta_key,$display='')
	{
		if ($display =='')
			$display = $umeta_key;
		return '( select `umeta_value` from '. DB_TABLE_USERMETA .' '.
				'where `umeta_user_id`=`user_id` and `umeta_key`=\''. $umeta_key .'\' ) as `'
				. $display .'` ';
	}
}