<?php
class CTL_admin_index extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->view->display('index.php');
	}
}