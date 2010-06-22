<?php
abstract class CTL_admin_Abstract extends CTL_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->acl->check('administrator'))
			$this->deny();
		$this->view->setAdmin();
	}
	
	public function notFound($request=array())
	{
		$this->locator->redirect('admin_error_not_found',$request);
	}
}