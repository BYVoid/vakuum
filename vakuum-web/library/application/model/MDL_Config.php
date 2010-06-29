<?php
class MDL_Config extends MDL_Parameter_Abstract
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return MDL_Config
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function __clone(){}
	protected function __construct()
	{
		$this->ids = array();
		$this->key_name = 'cfg_key';
		$this->value_name = 'cfg_value';
		$this->table_name = DB_TABLE_CONFIG;
		parent::initialize();
	}

	public function getVar($key)
	{
		$preference = BFL_Register::getVar('user_preference');
		if (isset($preference['key']))
			return $preference['key'];
		return parent::getVar($key);
	}
}