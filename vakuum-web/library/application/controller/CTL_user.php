<?php
require_once('CTL_Abstract/Controller.php');

/**
 * user Controller
 *
 * @author BYVoid
 */
class CTL_user extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('user_list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		$order_by = $this->path_option->getVar('by');
		$order = $this->path_option->getVar('order');

		$list = new MDL_User_List($page);
		$list->setOrder($order_by, $order);

		$this->view->list = $list;

		$this->view->display('user/list.php');
	}

	public function ACT_detail()
	{
		$user_name = $this->path_option->getPathSection(2);

		$user = new MDL_User($user_name, MDL_User::ID_USER_NAME, MDL_User::GET_ALL);

		$this->view->user = $user;
		$this->view->display('user/single.php');
	}

	public function ACT_space()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();
		$this->view->user = BFL_Register :: getVar('personal');
		$this->view->display('user_space.php');
	}

	public function ACT_edit()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();
		$this->view->user = BFL_Register :: getVar('personal');
		$this->view->display('user_edit.php');
	}

	public function ACT_doedit()
	{
		if (!$this->acl->check(array('general','unvalidated')))
			$this->deny();

		$user = BFL_Register :: getVar('personal');
		$user_id = $this->acl->getUser()->getID();
		$user_name = $user['user_name'];

		$user_info = array
		(
			'user_id'=> $user_id,
			'user_name' => $user_name,
			'user_password_correct' => $user['user_password'],
			'user_password' => $_POST['user_password'],
			'user_password_original'=> $_POST['user_password_original'],
			'user_password_repeat' => $_POST['user_password_repeat'],
			'user_nickname' => $_POST['user_nickname'],
			'email'	=> $_POST['email'],
			'website' => $_POST['website'],
			'memo' => $_POST['memo'],
			'identity' => $user['identity'],
		);

		MDL_User_Edit::edit($user_info);

		//TODO success message
		$this->locator->redirect('user_space');
	}
}