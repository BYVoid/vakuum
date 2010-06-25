<?php
/**
 * Manage user meta
 *
 * @author BYVoid
 */
class MDL_User_Meta extends MDL_Parameter_Abstract
{
	public function __construct($user_id)
	{
		$this->ids = array
		(
			'umeta_user_id' => (int) $user_id,
		);
		$this->key_name = 'umeta_key';
		$this->value_name = 'umeta_value';
		$this->table_name = DB_TABLE_USERMETA;
		parent::initialize();
	}
}