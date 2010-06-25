<?php
/**
 * Manage problem meta
 *
 * @author BYVoid
 */
class MDL_Contest_Meta extends MDL_Parameter_Abstract
{
	public function __construct($contest_id, $user_id)
	{
		$this->ids = array
		(
			'cmeta_contest_id' => (int) $contest_id,
			'cmeta_user_id' => (int) $user_id,
		);
		$this->key_name = 'cmeta_key';
		$this->value_name = 'cmeta_value';
		$this->table_name = DB_TABLE_CONTESTMETA;
		parent::initialize();
	}
}