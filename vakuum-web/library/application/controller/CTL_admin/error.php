<?php
class CTL_admin_error extends CTL_admin_Abstract
{
	public function SAC_otherAction()
	{
		$action = $this->path_option->getPathSection(1);
		$this->view->action = $action;
		$this->view->option = $this->path_option->getAll();
		$this->view->display('error.php');
	}
}