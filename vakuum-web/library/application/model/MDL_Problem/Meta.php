<?php
/**
 * Manage problem meta
 *
 * @author BYVoid
 */
class MDL_Problem_Meta extends MDL_Parameter_Abstract
{
	public function __construct($prob_id)
	{
		$this->ids = array
		(
			'pmeta_prob_id' => (int) $prob_id,
		);
		$this->key_name = 'pmeta_key';
		$this->value_name = 'pmeta_value';
		$this->table_name = DB_TABLE_PROBMETA;
		parent::initialize();
	}
}