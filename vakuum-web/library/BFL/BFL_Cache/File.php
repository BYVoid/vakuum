<?php
class BFL_Cache_File
{
	public static $enable_gzip = true;
	private $cache_path;
	private $expire;

	public function __construct($path, $expire = 86400)
	{
		$this->cache_path = $path;
		$this->expire = 86400;
	}

	private function getFilename($key)
	{
		return $this->cache_path . urlencode($key);
	}

	private function getInfoFilename($key)
	{
		return $this->getFilename($key).'_cacheinfo';
	}

	public function getExpire($key)
	{
		$filename = $this->getInfoFilename($key);
		if (file_exists($filename))
		{
			$info = explode("\n", $this->readFile($filename, false));
			return $info[0];
		}
		else
			return time();
	}

	public function remove($key)
	{
		$filename = $this->getFilename($key);
		if (file_exists($filename))
		{
			unlink($filename);
			unlink($this->getInfoFilename($key));
		}
	}

	public function exist($key)
	{
		$filename = $this->getFilename($key);

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
		$value = $this->readFile($this->getFilename($key));
		return unserialize($value);
	}

	public function set($key, $value, $expire = NULL)
	{
		if ($expire == NULL)
			$expire = $this->expire;
		$expire += time();
		$info = implode("\n", array(
			$expire, $key
		));

		$value = serialize($value);

		$this->writeFile($this->getFilename($key), $value);
		$this->writeFile($this->getInfoFilename($key), $info, false);
	}

	private function readFile($filename, $gzip = true)
	{
		$contents = file_get_contents($filename);
		if ($gzip && self::$enable_gzip)
			$contents = gzinflate($contents);
		return $contents;
	}

	private function writeFile($filename, $contents, $gzip = true)
	{
		if ($gzip && self::$enable_gzip)
			$contents = gzdeflate($contents);
		file_put_contents($filename, $contents);
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