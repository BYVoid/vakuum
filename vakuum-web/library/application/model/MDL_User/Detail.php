<?php
/**
 * Show a user
 *
 * @author BYVoid
 */
class MDL_User_Detail
{
	/**
	 * Get User By ID
	 * @param int $user_id
	 * @return array User
	 */
	public static function getUser($user_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_USER.' where `user_id`=:user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
		{
			throw new MDL_Exception_User('id');
		}

		$user = $rs;

		$user_meta = new MDL_User_Meta($user_id);
		$rs = $user_meta->getAll();
		$user = array_merge($user,$rs);
		
		return $user;
	}
	
	/**
	 * Get User By Name
	 * @param string $user_name User Name
	 * @return array User
	 */
	public static function getUserByName($user_name)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_USER.' where `user_name`=:user_name');
		$stmt->bindParam(':user_name', $user_name);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
		{
			throw new MDL_Exception_User('name');
		}

		$user = $rs;
		$user_id = $user['user_id'];

		$user_meta = new MDL_User_Meta($user_id);
		$rs = $user_meta->getAll();
		$user = array_merge($user,$rs);
		
		return $user;
	}
	
	private static function getMetaSQL($umeta_key,$display='')
	{
		if ($display =='')
			$display = $umeta_key;
		return '( select `umeta_value` from '. DB_TABLE_USERMETA .' '.
				'where `umeta_user_id`=`user_id` and `umeta_key`=\''. $umeta_key .'\' ) as `'
				. $display .'` ';
	}
	
	public static function getUserName($user_id)
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `user_name`,`user_nickname` from '.DB_TABLE_USER.' WHERE `user_id` = :user_id');
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
		{
			throw new MDL_Exception_User('id');
		}
		
		return $rs;
	}
}