<?php
class MDL_Cache
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return MDL_ACL
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private $cache_path;
	private $default_expire;

	private function __clone(){}
	private function __construct()
	{
		$prefix = '/tmp/vakuum/';
		if (!is_dir($prefix))
			mkdir($prefix);

		$this->cache_path = $prefix.CACHE_PREFIX.'/';
		if (!is_dir($this->cache_path))
			mkdir($this->cache_path);

		$this->default_expire = 60;
	}

	public function getExpire($key)
	{
		$filename = $this->cache_path . $key . '_expire';
		if (file_exists($filename))
			return (int) file_get_contents($filename);
		else
			return time();
	}

	public function exist($key)
	{
		$filename = $this->cache_path . $key;

		if (file_exists($filename))
		{
			if (time() >= $this->getExpire($key))
			{
				unlink($filename);
				return false;
			}
			return true;
		}
		else
			return false;
	}

	public function get($key, $default_value = NULL)
	{
		if (!$this->exist($key))
		{
			$this->set($key, $default_value);
			return $default_value;
		}
		return file_get_contents($this->cache_path . $key);
	}

	public function set($key, $value, $expire = NULL)
	{
		if ($expire == NULL)
			$expire = $this->default_expire;
		$expire += time();
		file_put_contents($this->cache_path . $key, $value);
		file_put_contents($this->cache_path . $key . '_expire', $expire);
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
}