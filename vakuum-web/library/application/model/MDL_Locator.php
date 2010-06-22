<?php
/**
 * Description of MDL_View
 *
 * @author BYVoid
 */
class MDL_Locator
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return MDL_Locator
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private $_path;

	private function __clone(){}

	private function __construct()
	{
		$this->_path = parse_ini_file('library/path.ini.php');
	}

	public function getFilePath($key)
	{
		if (isset($this->_path[$key]))
			return $this->_path[$key];
		else
			throw new MDL_Exception('not found');
	}
	
	private static function makeOptions($path_options=array(),$qs_options=array())
	{
		$path = '';
		foreach ($path_options as $key=>$value)
		{
			$path .= '/'.$key.'/'.$value;
		}
		if (!empty($qs_options))
		{
			$path.='?'.http_build_query($qs_options,'','&');
		}
		return $path;
	}
	
	public static function makePublicURL($base,$path_options=array(),$qs_options=array())
	{
		$config = MDL_Config::getInstance();
		
		$path = $config->getVar('root_path') . $base;
		
		$bind_address = $config->getVar('site_address');
		if ($bind_address != '')
			$path = $bind_address . $path;
		
		$path .= self::makeOptions($path_options,$qs_options);
		
		return $path;
	}
	
	public static function makeURL($base,$path_options=array(),$qs_options=array())
	{
		$config = MDL_Config::getInstance();
		
		$path = $config->getVar('root_path'). $config->getVar('root_path_prefix') . $base;
		
		$bind_address = $config->getVar('site_address');
		if ($bind_address != '')
			$path = $bind_address . $path;
		
		$path .= self::makeOptions($path_options,$qs_options);
		
		return $path;
	}
	
	public function getURL($key,$path_options=array(),$qs_options=array())
	{
		if (isset($this->_path[$key]))
			$base =  $this->_path[$key];
		else
			throw new MDL_Exception('not found');
		return self::makeURL($base,$path_options,$qs_options);
	}

	public function redirect($key,$request=array(),$path_addition='')
	{
		$query = '';
		if (!empty($request))
			$query = '?'.htmlspecialchars_decode(http_build_query($request));
		BFL_Controller::redirect($this->getURL($key). $path_addition . $query);
	}
}
?>
