<?php
/**
 * Abstract class to manage meta
 *
 * @author BYVoid
 */
abstract class MDL_Parameter_Abstract
{
	/**
	 * Array to store metas
	 * @var array
	 */
	protected $_array;
	protected $id = false;
	protected $key_name,$value_name,$table_name,$id_name;
	
	protected function initialize()
	{
		//Read database to fetch all metas of $id
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory("select `{$this->key_name}`,`{$this->value_name}` from {$this->table_name} where `{$this->id_name}` = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();
		while ($rs = $stmt->fetch())
			$this->_array[ $rs[ $this->key_name ] ] = $rs[ $this->value_name ];
	}

	/**
	 * Set varible
	 * @param string $key
	 * @param varible $val
	 */
	public function setVar($key,$value)
	{
		$db = BFL_Database :: getInstance();
		if ($this->id !== false)
			$id = $this->id;

		if (!isset($this->_array[$key]))
		{
			//not exist add Meta
			$meta = array
			(
				$this->key_name => ':key',
				$this->value_name => ':value',
			);
			if (isset($id))
			{
				$meta[$this->id_name] = ':id';
			}
			$stmt = $db->insert($this->table_name , $meta);
		}
		else
		{
			//already exist edit Meta
			$meta = array
			(
				"`{$this->value_name}` = :value",
			);
			$condition = "WHERE `{$this->key_name}`=:key";
			if (isset($id))
			{
				$condition .= " and `{$this->id_name}`=:id";
			}
			$stmt = $db->update($this->table_name , $meta ,$condition);
		}
		if (isset($id))
		{
			$stmt->bindParam(':id', $id);
		}
		$stmt->bindParam(':key', $key);
		$stmt->bindParam(':value', $value);
		$stmt->execute();
		
		$this->_array[$key] = $value;
	}

	/**
	 * Get varible
	 * @param string $key
	 * @return varible Value
	 */
	public function getVar($key)
	{
		if (!isset($this->_array[$key]))
			$this->_array[$key] = '';
		return $this->_array[$key];
	}

	/**
	 * Get all varibles
	 * @return array All varibles
	 */
	public function getAll()
	{
		if (empty($this->_array))
			$this->_array = array();
		return $this->_array;
	}

	/**
	 * Set a group of metas
	 * @param array $items
	 */
	public function setMetas($items)
	{
		$db = BFL_Database::getInstance();
		$db->beginTransaction();
		foreach ($items as $key => $value)
		{
			$this->setVar($key,$value);
		}
		$db->commit();
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
	 * unset var $key
	 * @param string $key
	 */
	public function unsetVar($key)
	{
		if (!isset($this->_array[$key]))
			return;
		unset($this->_array[$key]);
		$db = BFL_Database :: getInstance();
		$stmt = $db->delete($this->table_name,"where {$this->key_name}=:key");
		$stmt->bindParam(':key',$key);
		$stmt->execute();
	}
}