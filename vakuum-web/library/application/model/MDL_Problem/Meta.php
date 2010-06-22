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
		$this->id = $prob_id;
		$this->key_name = 'pmeta_key';
		$this->value_name = 'pmeta_value';
		$this->table_name = DB_TABLE_PROBMETA;
		$this->id_name = 'pmeta_prob_id';
		parent::initialize();
	}
}