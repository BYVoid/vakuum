<?php
require_once("Abstract.php");

class CTL_admin_error extends CTL_admin_Abstract
{
	public function SAC_otherAction()
	{
		$desc = BFL_Serializer::transmitDecode($this->path_option->getQueryString());
		
		$this->view->error = $desc; 
		$this->view->display('error.php');
	}
}