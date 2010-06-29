<?php

class MDL_Record_Display
{
	static protected $property = array
	(
		'information',
		'compile',
		'result',
		'case',
		'source',
	);

	public $information = false;
	public $compile = false;
	public $result = false;
	public $case = false;
	public $source = false;

	public function encode()
	{
		$ret = 0;
		foreach (self::$property as $key)
		{
			if ($this->$key)
				$ret = ($ret + 1) * 2;
			else
				$ret *= 2;
		}
		return $ret / 2;
	}

	public static function decode($code ,$inst = false)
	{
		if ($inst === false)
			$inst = new self();

		for ($i = count(self::$property) - 1; $i >=0; -- $i)
		{
			$key = self::$property[$i];
			$inst->$key = $code & 1;
			$code = (int) $code / 2;
		}
		return $inst;
	}

	public function __construct($code = false)
	{
		if ($code !== false)
			self::decode($code, $this);
	}

	public function setAll($bool = true)
	{
		foreach (self::$property as $key)
			$this->$key = $bool;
	}
}