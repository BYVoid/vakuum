<?php
require_once("Abstract.php");

class CTL_admin_user extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_user_list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page === false)
			$page = 1;

		$rs = new MDL_User_List($page);

		$this->view->list = $rs;

		$this->view->display('user_list.php');
	}

	public function ACT_edit()
	{
		$user_id = (int)$this->path_option->getPathSection(2);

		if ($user_id != 0)
		{
			$rs = MDL_User_Detail::getUser($user_id);

			$rs['action'] = 'edit';
		}
		else
		{
			$rs = array
			(
				'user_id' => 0,
				'user_name' => '',
				'user_nickname' => '',
				'email' => '',
				'website' => '',
				'memo' => '',
				'identity' => 'general',
			);

			$rs['action'] = 'add';
		}

		$this->view->user = $rs;
		$this->view->display('user_edit.php');
	}

	public function ACT_doedit()
	{
		$identity=array();
		foreach ($_POST['identity'] as $key => $value)
			if ($value == 1)
				$identity[] = $key;
		$identity = implode(',',$identity);

		$user = array
		(
			'user_id' => $_POST['user_id'],
			'user_name' => $_POST['user_name'],
			'user_nickname' => $_POST['user_nickname'],
			'user_password' => $_POST['user_password'],
			'user_password_repeat' => $_POST['user_password'],
			'email' => $_POST['email'],
			'website' => $_POST['website'],
			'memo' => $_POST['memo'],
			'identity' => $identity,
		);

		if ($_POST['action'] == 'add')
		{
			//Add New User
			MDL_User_Edit::create($user);
		}
		else if ($_POST['action'] == 'edit')
		{
			if (isset($_POST['remove']) && $_POST['remove']==1)
				//Remove User
				MDL_User_Edit::remove($_POST['user_id']);
			else
				//Edit User
				MDL_User_Edit::edit($user,false);
		}
		else
			$this->deny();

		$this->locator->redirect('admin_user_list');
	}
}