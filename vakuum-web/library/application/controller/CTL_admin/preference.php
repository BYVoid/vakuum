<?php
class CTL_admin_preference extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->view->preferences = MDL_Preference::getPreferences();
		$this->view->display('preference.php');
	}
	
	public function ACT_doedit()
	{
		if ($_GET['action'] == 'preferences')
		{
			MDL_Preference::editPreferences($_POST);
			$this->locator->redirect('admin_preference');
		}
	}
}