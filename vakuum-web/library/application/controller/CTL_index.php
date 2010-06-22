<?php
require_once('CTL_Abstract/Controller.php');

class CTL_index extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->view->display('index.php');
	}
}