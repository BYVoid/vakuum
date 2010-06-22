<?php
require_once('MDL_View/Abstract.php');
/**
 * Description of MDL_View
 *
 * @author BYVoid
 */
class MDL_View extends MDL_View_Abstract
{
	protected static $_instance = NULL;
	
	/**
	 * Get Instance
	 * @return MDL_View
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected $locator;

	private function __clone(){}

	private function __construct()
	{
		$this->locator = MDL_Locator ::getInstance();
		$this->header['javascript'] = $this->header['stylesheet'] = array();
		//設置默認時區
		date_default_timezone_set(MDL_Config::getInstance()->getVar('time_zone'));
	}

	public function setTheme($theme)
	{
		$theme_path = 'public/themes/'.$theme.'/';
		$this->setViewPath($theme_path);
	}
	
	public function setAdmin()
	{
		$theme_path = 'public/admin/';
		$this->setViewPath($theme_path);
	}
	
	public function display($script)
	{
		parent :: display($script);
	}
}