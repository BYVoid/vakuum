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

		$this->default_expire = 86400;
	}

	public function keyEncode($key)
	{
		return sha1($key);
	}

	public function getPath($key)
	{
		return $this->cache_path . $this->keyEncode($key);
	}

	public function getExpire($key)
	{
		$filename = $this->getPath($key) . '_expire';
		if (file_exists($filename))
			return (int) file_get_contents($filename);
		else
			return time();
	}

	public function exist($key)
	{
		$filename = $this->getPath($key);

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
		$value = file_get_contents($this->getPath($key));
		return unserialize($value);
	}

	public function set($key, $value, $expire = NULL)
	{
		if ($expire == NULL)
			$expire = $this->default_expire;
		$expire += time();

		$value = serialize($value);

		file_put_contents($this->getPath($key), $value);
		file_put_contents($this->getPath($key) . '_expire', $expire);
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