<?php
class BFL_Loader
{
	protected static $BFL_path,$controller_path,$model_path;

	protected static function loadClass($field, $field_path, $class_name)
	{
		$part = explode('_',$class_name);
		$label = $part[0];
		unset($part[0]);
		if ($label == $field)
		{
			$path = implode('/',$part);
			if ($path != '')
				$path = '_'.$path;
			$path = $field_path . $label . $path . '.php';
			require_once($path);
		}
	}

	public static function loadBFL($class_name)
	{
		self::loadClass('BFL', self::$BFL_path, $class_name);
	}

	public static function loadController($class_name)
	{
		self::loadClass('CTL', self::$controller_path, $class_name);
	}

	public static function loadModel($class_name)
	{
		self::loadClass('MDL', self::$model_path, $class_name);
	}

	public static function setBFLPath($path)
	{
		if ($path[strlen($path) - 1] != '/')
			$path .= '/';
		self::$BFL_path = $path;
		spl_autoload_register(array('self','loadBFL'));
	}

	public static function setControllerPath($path)
	{
		if ($path[strlen($path) - 1] != '/')
			$path .= '/';
		self::$controller_path = $path;
		spl_autoload_register(array('self','loadController'));
	}

	public static function setModelPath($path)
	{
		if ($path[strlen($path) - 1] != '/')
			$path .= '/';
		self::$model_path = $path;
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