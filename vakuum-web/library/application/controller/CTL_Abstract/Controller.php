<?php
/**
 * Abstract Controller Class
 *
 * @package Common Controller
 */
abstract class CTL_Abstract_Controller
{
	/**
	 * The view object
	 * @var MDL_View
	 */
	protected $view;

	/**
	 * The config object
	 * @var MDL_Config
	 */
	protected $config;
	
	/**
	 * The ACL object
	 * @var BFL_ACL
	 */
	protected $acl;

	/**
	 * Location
	 * @var MDL_Locator
	 */
	protected $locator;

	/**
	 * Request path given by browser
	 * @var BFL_PathOption
	 */
	protected $path_option;


	/**
	 * Construct and initilize
	 */
	public function __construct()
	{
		$this->config = MDL_Config :: getInstance();
		$this->acl = BFL_ACL :: getInstance();
		$this->locator = MDL_Locator :: getInstance();
		$this->view = MDL_View::getInstance();
		
		$this->view->setTheme( $this->config->getVar('theme') );
		$this->path_option = BFL_PathOption::getInstance();
	}
	
	public function deny()
	{
		$this->locator->redirect('error_permission_denied');
	}
	
	public function notFound($request=array())
	{
		$this->locator->redirect('error_not_found',$request);
	}
}