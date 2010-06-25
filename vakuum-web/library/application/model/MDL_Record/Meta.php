<?php
/**
 * Manage Record meta
 *
 * @author BYVoid
 */
class MDL_Record_Meta extends MDL_Parameter_Abstract
{
	public function __construct($record_id)
	{
		$this->ids = array
		(
			'rmeta_record_id' => (int) $record_id,
		);
		$this->key_name = 'rmeta_key';
		$this->value_name = 'rmeta_value';
		$this->table_name = DB_TABLE_RECORDMETA;
		parent::initialize();
	}
}