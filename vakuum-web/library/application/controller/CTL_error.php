<?php
require_once('CTL_Abstract/Controller.php');

class CTL_error extends CTL_Abstract_Controller
{
	public function SAC_otherAction()
	{
		$action = $this->path_option->getPathSection(1);
		switch($action)
		{
			case 'notfound':
				echo 'The page is not found!';
				break;
			case 'permissiondenied':
				echo 'Permission Denied!';
				break;
			default:
				echo 'Unknown Error: '. $action;
		}
		var_dump($this->path_option->getAll());
	}
	
}