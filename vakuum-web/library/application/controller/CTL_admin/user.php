<?php
class CTL_admin_user extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_user_list');
	}
	
	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		try
		{
			$rs = MDL_User_List::getList($page);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'user_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->display('user_list.php');
	}
	
	public function ACT_edit()
	{
		$user_id = (int)$this->path_option->getPathSection(2);
		
		if ($user_id != 0)
		{
			try
			{
				$rs = MDL_User_Detail::getUser($user_id);
			}
			catch(MDL_Exception_User $e)
			{
				$desc = $e->getDescription();
				if ($desc[1] == 'id')
					$this->notFound(array('specifier' => 'user'));
				else
					throw $e;
			}
			
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
		try
		{
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
		catch(MDL_Exception_User $e)
		{
			$desc = $e->getDescription();
			$request['specifier'] = $desc[2];
			$this->locator->redirect('admin_error_user_edit',$request);
		}
	}
}