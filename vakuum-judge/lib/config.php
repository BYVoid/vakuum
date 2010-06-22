<?php
class Config
{
	protected static $_instance = NULL;
	protected $config;
	/**
	 * getInstance
	 * @return Config
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected function __clone(){}
	protected function __construct()
	{
		$xml = file_get_contents("config.xml");
		$this->config = BFL_XML::XML2Array($xml);
	}
	
	public function getVar($key)
	{
		return $this->config[$key];
	}
	
};