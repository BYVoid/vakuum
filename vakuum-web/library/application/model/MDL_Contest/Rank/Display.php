<?php

class MDL_Contest_Rank_Display
{
	static protected $property = array
	(
		'list',
		'user',
		'score',
		'penalty',
		'problem',
	);

	public $list = false;
	public $user = false;
	public $score = false;
	public $penalty = false;
	public $problem = false;

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
}