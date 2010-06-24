<?php
abstract class CTL_admin_Abstract extends CTL_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!$this->acl->check('administrator'))
			$this->deny();
		$this->view->setAdmin();
		MDL_ExceptionHandler::setErrorPage('admin/error');
	}
}