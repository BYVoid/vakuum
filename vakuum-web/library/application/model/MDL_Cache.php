<?php
class MDL_Cache extends BFL_Cache
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return MDL_Cache
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __clone(){}
	private function __construct()
	{
		$this->addProcesser(new BFL_Cache_Runtime());

		$prefix = sys_get_temp_dir().'/vakuum/';
		if (!is_dir($prefix))
			mkdir($prefix);

		$cache_path = $prefix.CACHE_PREFIX.'/';
		if (!is_dir($cache_path))
			mkdir($cache_path);

		$this->addProcesser(new BFL_Cache_File($cache_path));
	}
}