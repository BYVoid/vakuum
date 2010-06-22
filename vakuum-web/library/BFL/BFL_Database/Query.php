<?php
class BFL_Database_Query
{
	private static $queryCount = 0;

	public static function getQueryCount()
	{
		return self :: $queryCount;
	}

	/**
	 * 
	 * @var PDOStatement
	 */
	private $stmt;

	public function __construct($SQL,$pdo)
	{
		$this->stmt = $pdo->prepare($SQL);
	}

	public function bindParam($key,&$val)
	{
		$this->stmt->bindParam($key,$val);
	}

	public function execute()
	{
		try
		{
			$this->stmt->execute();
			$this->stmt->setFetchMode(PDO::FETCH_ASSOC);
			++self::$queryCount;
		}
		catch(PDOException $e)
		{
			echo $this->stmt->queryString;
			throw $e;
		}
	}

	public function fetch()
	{
		return $this->stmt->fetch();
	}

	public function fetchAll()
	{
		return $this->stmt->fetchAll();
	}
	
	public function rowCount()
	{
		return $this->stmt->rowCount();
	}
}