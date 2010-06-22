<?php
require_once('BFL_Database/Abstract.php');
/**
 * Database access object
 * Singleton Class
 * @author BYVoid
 */
class BFL_Database extends BFL_Database_Abstract
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return BFL_Database
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function getLastInsertID()
	{
		foreach ($this->pdo->query('SELECT LAST_INSERT_ID()') as $rs)
			return $rs[0];
	}
	
	public function getQueryCount()
	{
		return BFL_Database_Query :: getQueryCount();
	}
	
	public function beginTransaction()
	{
		$this->pdo->beginTransaction();
	}
	
	public function commit()
	{
		$this->pdo->commit();
	}
	
	public function rollback()
	{
		$this->pdo->rollback();
	}
}
