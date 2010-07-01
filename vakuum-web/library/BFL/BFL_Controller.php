<?php
class BFL_Controller
{
	protected static $_instance = NULL;

	/**
	 * Get Instance
	 * @return BFL_Controller
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function redirect($address)
	{
		@header('Location: '.$address);
		echo '<p>Redirecting to <a href="'.$address.'">'.$address.'</a></p>';
		exit;
	}

	private $nf_class,$nf_method;
	private $custom_controller_router;
	private $request_path,$base_path;
	private $controller_prefix,$action_prefix;

	private function __clone(){}

	private function __construct()
	{
		$this->custom_controller_router=array();
		$this->request_path = $this->getPathInfo();
		$this->controller_prefix = 'CTL';
		$this->action_prefix = 'ACT';
	}

	private function notFound()
	{
		if (isset($this->not_found_callback))
		{
			$nfmtd = $this->not_found_callback[1];
			$hdl = new $this->not_found_callback[0];
			$hdl->$nfmtd();
		}
		else
			die('not found');
	}

	public function setNotFound($callback)
	{
		$this->not_found_callback = $callback;
	}

	public function dispatch()
	{
		$this->actCustomRouter();
		$this->formatPath();
		$path_option = BFL_PathOption::getInstance();

		$class_name = $this->controller_prefix.'_'.$path_option->getPathSection(0);
		$action_name = $this->action_prefix.'_'.$path_option->getPathSection(1);

		if (BFL_Loader::controllerExist($class_name))
		{
			$ctl = new $class_name;
			if (method_exists($ctl,$action_name))
				$ctl->$action_name();
			else
			{
				if (method_exists($ctl,'SAC_otherAction'))
					$ctl->SAC_otherAction();
				else
					$this->notFound();
			}
		}
		else
			$this->notFound();
	}

	private function getPathInfo()
	{
		if (!isset($_SERVER['PATH_INFO']))
			return '';
		return $_SERVER['PATH_INFO'];
	}

	private function actCustomRouter()
	{
		foreach ($this->custom_controller_router as $item)
		{
			$prefix = $item[0];
			$controller = $item[1];
			$pos = strpos($this->request_path,$prefix);
			if ($pos === 0)
			{
				if (strlen($prefix) == strlen($this->request_path) || $this->request_path[strlen($prefix)] == '/')
				{
					$this->request_path = substr($this->request_path,strlen($prefix));
					$this->controller_prefix .= $controller;
					$this->base_path .= $prefix;
					return;
				}
			}
		}
	}

	private function formatPath()
	{
		$this->request_path = explode('/',$this->request_path);
		unset($this->request_path[0]);

		foreach($this->request_path as $key => $value)
		{
			$this->request_path[$key] = strtolower($value);
		}
		BFL_Register::setVar('request_path',$this->request_path);
		BFL_Register::setVar('base_path',$this->base_path);
	}

	public function setCustomControllerRouter($path_prefix,$controller_name)
	{
		$this->custom_controller_router[] = array($path_prefix,$controller_name);
	}
}