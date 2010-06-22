<?php
/**
 * Prase path options and query string
 *
 * @author BYVoid
 */
class BFL_PathOption
{
	protected static $_instance = NULL;
	private $base_path,$path_option,$query_string;
	private $request_path;
	
	/**
	 * getInstance
	 * @return BFL_PathOption
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
		$this->query_string = $_GET;
		$this->request_path = BFL_Register::getVar('request_path');
		$this->base_path = BFL_Register::getVar('base_path');
		BFL_Register::unsetVar('request_path');
		BFL_Register::unsetVar('base_path');
		
		$this->base_path .= '/'.$this->getPathSection(0).'/'.$this->getPathSection(1);
		if ($this->base_path[0] == '/')
			$this->base_path = substr($this->base_path,1);
		
		$path = $this->request_path;
		unset($path[1],$path[2]);
		
		$iskey = false;
		$key = '';
		$result = array();
		foreach ($path as $item)
		{
			$iskey =!$iskey;
			if ($iskey)
				$key = $item;
			else
				$result[$key] = $item;
		}
		$this->path_option = $result;
	}
	
	public function getPathSection($index)
	{
		++$index;
		if ($index == 1 || $index == 2)
		{
			if (!isset($this->request_path[$index]) || $this->request_path[$index] == '')
				$this->request_path[$index] = 'index';
		}
		else if (!isset($this->request_path[$index]))
			$this->request_path[$index] = '';
		return $this->request_path[$index];
	}
	
	public function getURL($options=array())
	{
		$path_option = $query_string = array();

		foreach($this->path_option as $key=>$value)
			if (isset($options[$key]))
			{
				$path_option[$key] = $options[$key];
				unset($options[$key]);
			}
			else
				$path_option[$key] = $value;

		foreach($this->query_string as $key=>$value)
			if (isset($options[$key]))
			{
				$query_string[$key] = $options[$key];
				unset($options[$key]);
			}
			else
				$query_string[$key] = $value;

		foreach($options as $key=>$value)
			$path_option[$key] = $value;
		
		return MDL_Locator::makeURL($this->base_path,$path_option,$query_string);
	}
	
	public function getVar($key)
	{
		if (isset($this->path_option[$key]))
			return $this->path_option[$key];
		if (isset($this->query_string[$key]))
			return $this->query_string[$key];
		return false;
	}
	
	public function getAll()
	{
		return array_merge($this->path_option,$this->query_string);
	}
}