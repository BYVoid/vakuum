<?php
/**
 * Abstract class to manage meta
 *
 * @author BYVoid
 */
abstract class MDL_Parameter_Abstract
{
	const ADD = 0;
	const MODIFY = 1;
	const REMOVE = 2;

	/**
	 * Array to store metas
	 * @var array
	 */
	protected $varibles, $modified;
	protected $key_name,$value_name,$table_name,$ids;
	protected $condition, $idmeta;

	protected function initialize()
	{
		$this->idmeta = array();
		$condition = "(";

		if (is_array($this->ids))
		{
			foreach($this->ids as $key => $value)
			{
				$condition .= "`{$key}` = {$value} and ";
				$this->idmeta[$key] = $value;
			}
		}

		$condition .= " 1)";
		$this->condition = $condition;

		//Read database to fetch all metas of $ids
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory("select `{$this->key_name}`,`{$this->value_name}` from {$this->table_name} where {$condition}");
		$stmt->execute();

		$this->varibles = array();
		$this->modified = array();

		while ($rs = $stmt->fetch())
			$this->varibles[ $rs[ $this->key_name ] ] = $rs[ $this->value_name ];
	}

	public function __destruct()
	{
		$this->sync();
	}

	public function sync()
	{
		if (count($this->modified) == 0)
			return;
		foreach ($this->modified as $key => $action)
		{
			$this->writeVar($key, $this->varibles[$key], $action);
		}
		$this->modified = array();
	}

	/**
	 * judge whether var $key is set
	 * @param string $key
	 * @return bool
	 */
	public function haveVar($key)
	{
		return isset($this->varibles[$key]);
	}

	/**
	 * Set varible
	 * @param string $key
	 * @param varible $val
	 */
	public function setVar($key,$value)
	{
		if ($this->haveVar($key))
		{
			if ($this->varibles[$key] == $value)
				return;

			if (!isset($this->modified[$key]))
				$this->modified[$key] = self::MODIFY;
		}
		else
		{
			if (!isset($this->modified[$key]))
				$this->modified[$key] = self::ADD;
			else
				$this->modified[$key] = self::MODIFY;
		}
		$this->varibles[$key] = $value;
	}

	/**
	 * unset var $key
	 * @param string $key
	 */
	public function unsetVar($key)
	{
		if (!$this->haveVar($key))
		{
			throw new MDL_Exception_Meta(MDL_Exception_Meta::NON_EXISTENT_VARIBLE);
		}
		unset($this->varibles[$key]);
		$this->modified[$key] = self::REMOVE;
	}

	/**
	 * Get varible
	 * @param string $key
	 * @return varible Value
	 */
	public function getVar($key)
	{
		if (!$this->haveVar($key))
		{
			if (DEBUG)
			{
				var_dump($key);
			}
			throw new MDL_Exception_Meta(MDL_Exception_Meta::NON_EXISTENT_VARIBLE);
		}
		return $this->varibles[$key];
	}

	/**
	 * Get all varibles
	 * @return array All varibles
	 */
	public function getAll()
	{
		return $this->varibles;
	}

	/**
	 * Set a group of metas
	 * @param array $items
	 */
	public function setVars($items)
	{
		foreach ($items as $key => $value)
		{
			$this->setVar($key,$value);
		}
	}

	public function __get($key)
	{
		return $this->getVar($key);
	}

	public function __set($key, $value)
	{
		$this->setVar($key, $value);
	}

	public function __isset($key)
	{
		return $this->haveVar($key);
	}

	public function __unset($key)
	{
		$this->unsetVar($key);
	}

	protected function writeVar($key, $value, $action)
	{
		$db = BFL_Database :: getInstance();

		switch($action)
		{
			case self::ADD:

				$meta = array
				(
					$this->key_name => ':key',
					$this->value_name => ':value',
				);
				$meta = array_merge($meta, $this->idmeta);

				$stmt = $db->insert($this->table_name , $meta);
				$stmt->bindParam(':key', $key);
				$stmt->bindParam(':value', $value);

				break;

			case self::MODIFY:

				$meta = array
				(
					"`{$this->value_name}` = :value",
				);
				$condition = "WHERE `{$this->key_name}`=:key and {$this->condition}";
				$stmt = $db->update($this->table_name , $meta ,$condition);
				$stmt->bindParam(':key', $key);
				$stmt->bindParam(':value', $value);
				break;

			case self::REMOVE:

				$stmt = $db->delete($this->table_name,"where `{$this->key_name}`=:key and {$this->condition}");
				$stmt->bindParam(':key', $key);

				break;

			default:
				throw new MDL_Exception_Meta(MDL_Exception_Meta::INVALID_WRITE_ACTION);
		}
		$stmt->execute();
	}
}