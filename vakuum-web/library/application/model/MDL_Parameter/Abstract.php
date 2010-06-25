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
	protected $_array, $modified;
	protected $key_name,$value_name,$table_name,$ids;
	protected $condition, $idmeta;
	
	protected function initialize()
	{
		$condition = "";
		$this->idmeta = array();
		
		if (is_array($this->ids) && count($this->ids) > 0)
		{
			$condition = "(";
			foreach($this->ids as $key => $value)
			{
				$condition .= "`{$key}` = {$value} and ";
				$this->idmeta[] = array($key => $value);
			}
			$condition .= " 1)";
		}
		
		$this->condition = $condition;
		if ($condition != "")
			$condition = "WHERE ". $condition;
		
		//Read database to fetch all metas of $ids
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory("select `{$this->key_name}`,`{$this->value_name}` from {$this->table_name} {$condition}");
		$stmt->execute();
		
		$this->_array = array();
		$this->modified = array();
		
		while ($rs = $stmt->fetch())
			$this->_array[ $rs[ $this->key_name ] ] = $rs[ $this->value_name ];
	}
	
	public function __destruct()
	{
		$this->sync();
	}

	public function sync()
	{
		if (count($this->modified) == 0)
			return;
		foreach ($this->modified as $key => $value)
		{
			$this->writeVar($key, $this->_array[$key], $value);
		}
	}
	
	/**
	 * judge whether var $key is set
	 * @param string $key
	 * @return bool
	 */
	public function haveVar($key)
	{
		return isset($this->_array[$key]);
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
			if ($this->_array[$key] == $value)
				return;
			$this->modified[$key] = self::MODIFY;
		}
		else
			$this->modified[$key] = self::ADD;
		$this->_array[$key] = $value;
	}
	
	/**
	 * unset var $key
	 * @param string $key
	 */
	public function unsetVar($key)
	{
		if (!$this->haveVar($key))
			return;
		unset($this->_array[$key]);
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
			$this->_array[$key] = false;
		return $this->_array[$key];
	}

	/**
	 * Get all varibles
	 * @return array All varibles
	 */
	public function getAll()
	{
		return $this->_array;
	}

	/**
	 * Set a group of metas
	 * @param array $items
	 */
	public function setMetas($items)
	{
		foreach ($items as $key => $value)
		{
			$this->setVar($key,$value);
		}
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