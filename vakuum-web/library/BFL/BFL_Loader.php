<?php
class BFL_Loader
{
	protected static $BFL_path,$controller_path,$model_path;
	
	public static function loadBFL($class_name)
	{
		$part = explode('_',$class_name);
		$label = $part[0];
		unset($part[0]);
		if ($label == 'BFL')
		{
			$path = implode('/',$part);
			$path = self::$BFL_path . $label . '_' . $path . '.php';
			require_once($path);
		}
	}
	
	public static function loadController($class_name)
	{
		$part = explode('_',$class_name);
		$label = $part[0];
		unset($part[0]);
		if ($label == 'CTL')
		{
			$path = implode('/',$part);
			$path = self::$controller_path . $label . '_' . $path . '.php';
			require_once($path);
		}
	}
	
	public static function loadModel($class_name)
	{
		$part = explode('_',$class_name);
		$label = $part[0];
		unset($part[0]);
		if ($label == 'MDL')
		{
			$path = implode('/',$part);
			$path = self::$model_path . $label . '_' . $path . '.php';
			require_once($path);
		}
	}
	
	public static function setBFLPath($path)
	{
		self::$BFL_path = $path .'/';
		spl_autoload_register(array('self','loadBFL'));
	}
	
	public static function setControllerPath($path)
	{
		self::$controller_path = $path .'/';
		spl_autoload_register(array('self','loadController'));
	}
	
	public static function setModelPath($path)
	{
		self::$model_path = $path .'/';
		spl_autoload_register(array('self','loadModel'));
	}
	
	public static function setIncludePath($path)
	{
		set_include_path($path .PATH_SEPARATOR .get_include_path());
	}
	
	public static function controllerExist($class)
	{
		$class = str_replace('_','/',$class);
		$class[3] = '_';
		$class .= '.php';
		$file_name = self::$controller_path . $class;
		return file_exists($file_name);
	}
}