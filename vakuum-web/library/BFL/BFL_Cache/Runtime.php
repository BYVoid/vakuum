<?php
class BFL_Cache_Runtime
{
	private $cache = array();

	public function exist($key)
	{
		return isset($this->cache[$key]);
	}

	public function remove($key)
	{
		if ($this->exist($key))
			unset($this->cache[$key]);
	}

	public function get($key, $default_value = NULL)
	{
		if (!$this->exist($key))
		{
			$this->set($key, $default_value);
			return $default_value;
		}
		return $this->cache[$key];
	}

	public function set($key, $value)
	{
		$this->cache[$key] = $value;
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