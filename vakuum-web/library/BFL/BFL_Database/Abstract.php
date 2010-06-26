<?php
require_once('Query.php');

/**
 * Database access object
 * Singleton Class
 * @author BYVoid
 */
class BFL_Database_Abstract
{
	/**
	 * @var PDO
	 */
	protected $pdo;

	private function __clone(){}
	protected function __construct()
	{
		$this->connect();
	}

	protected function connect()
	{
		$db_info = BFL_Register::getVar('db_info');
		BFL_Register::unsetVar('db_info');
		try
		{
			$dsn = $db_info['type'].':host='.$db_info['host'].';dbname='.$db_info['name'];
			$this->pdo = new PDO($dsn, $db_info['user'], $db_info['password']);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->exec('SET NAMES UTF8');
		}
		catch (PDOException $e)
		{
			die ("Database Error: " . $e->getMessage());
		}
	}

	public function lock($table)
	{
		if (!is_array($table))
			$table = array($table);

		$sql = 'lock tables ';
		$sql .= implode(',',$table);

		try
		{
			$this->pdo->exec($sql);
		}
		catch (PDOException $e)
		{
			die ("Database Error: " . $e->getMessage());
		}
	}

	public function unlock()
	{
		$this->pdo->exec('unlock tables');
	}

	/**
	 *
	 * @param string $SQL
	 * @return BFL_Database_Query
	 */
	public function factory($SQL)
	{
		return new BFL_Database_Query($SQL,$this->pdo);
	}

	/**
	 *
	 * @param string $table
	 * @param string or array $sets
	 * @param string $clause
	 * @return BFL_Database_Query
	 */
	public function update($table,$sets,$clause)
	{
		if (is_array($sets))
			$sets = implode(', ',$sets);
		$SQL = "UPDATE {$table} SET {$sets} {$clause}";
		return $this->factory($SQL);
	}

	/**
	 *
	 * @param string $table
	 * @param array $items
	 * @return BFL_Database_Query
	 */
	public function insert($table,$items)
	{
		foreach($items as $key => $val)
		{
			$column[] = '`' . $key . '`';
			$values[] = $val;
		}
		$column = implode(',',$column);
		$values = implode(',',$values);
		$SQL = "INSERT INTO {$table} ( {$column} ) VALUES ( {$values} )";
		return $this->factory($SQL);
	}

	/**
	 *
	 * @param string $table
	 * @param string $clause
	 * @return BFL_Database_Query
	 */
	public function delete($table,$clause)
	{
		$SQL = "DELETE FROM {$table} {$clause}";
		return $this->factory($SQL);
	}
}
