<?php
/**
 * Exception Package
 *
 * @author BYVoid
 */
class MDL_Exception extends RuntimeException
{
	const FIELD_BASE = "base";
	const UNDEFINED = "undefined";
	const NOTFOUND = "notfound";
	const PERMISSION_DENIED = "permission_denied";

	protected $desc = array();

	public function __construct($message)
	{
		$this->desc[self::FIELD_BASE] = $message;
		parent :: __construct("Vakuum_Exception");
	}

	public function haveField($field)
	{
		return isset($this->desc[$field]);
	}

	public function getTopField()
	{
		$field = $value = self::FIELD_BASE;
		while (isset($this->desc[$value]))
		{
			$field = $value;
			$value = $this->desc[$field];
		}
		return $field;
	}

	public function getDesc($field = '')
	{
		if ($field == '')
			return $this->desc;
		else if (self::haveField($field))
			return $this->desc[$field];
		else
			die('Exception Error');
	}

	public function getTopDesc()
	{
		return self::getDesc(self::getTopField());
	}

	public function testDesc($field, $msg)
	{
		return (self::haveField($field)) && (self::getDesc($field) == $msg);
	}

	public function testTopDesc($msg)
	{
		return self::getTopDesc() == $msg;
	}
}