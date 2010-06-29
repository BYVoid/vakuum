<?php
abstract class MDL_BitProperty
{
	public function __toString()
	{
		return (string) $this->encode();
	}

	public function encode()
	{
		$ret = 0;
		foreach ($this->property as $key)
		{
			if ($this->$key)
				$ret = ($ret + 1) * 2;
			else
				$ret *= 2;
		}
		return $ret / 2;
	}

	public function decode($code)
	{
		$code = (int) $code;
		for ($i = count($this->property) - 1; $i >=0; -- $i)
		{
			$key = $this->property[$i];
			$this->$key = (bool) ($code & 1);
			$code = (int) $code / 2;
		}
	}

	public function __construct($code = false)
	{
		$this->property = array_keys(get_class_vars(get_class($this)));
		sort($this->property);

		$this->decode($code);
	}

	public function setAll($bool = true)
	{
		foreach ($this->property as $key)
			$this->$key = $bool;
	}
}