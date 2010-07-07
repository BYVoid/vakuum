<?php
class BFL_Cache
{
	private $processer = array();

	public function addProcesser($instance)
	{
		$this->processer[] = $instance;
	}

	public function exist($key)
	{
		foreach($this->processer as $processer)
		{
			if ($processer->exist($key))
				return true;
		}
		return false;
	}

	public function remove($key)
	{
		foreach($this->processer as $processer)
		{
			$processer->remove($key);
		}
	}

	public function get($key, $default_value = NULL)
	{
		foreach($this->processer as $processer)
		{
			$retval = $processer->get($key, $default_value);
			if ($retval !== NULL)
				return $retval;
		}
		return NULL;
	}

	public function set($key, $value)
	{
		foreach($this->processer as $processer)
		{
			$processer->set($key, $value);
		}
	}

	public function __get($key)
	{
		return $this->get($key);
	}

	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	public function __isset($key)
	{
		return $this->exist($key);
	}

	public function __unset($key)
	{
		return $this->remove($key);
	}
}