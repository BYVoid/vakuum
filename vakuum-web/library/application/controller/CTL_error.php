<?php
require_once('CTL_Abstract/Controller.php');

class CTL_error extends CTL_Abstract_Controller
{
	public function SAC_otherAction()
	{
		$desc = BFL_Serializer::transmitDecode($this->path_option->getQueryString());
		var_dump($desc);
	}
	
}