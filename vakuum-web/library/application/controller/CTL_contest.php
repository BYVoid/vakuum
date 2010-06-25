<?php
require_once('CTL_Abstract/Controller.php');

class CTL_contest extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('contest_list');
	}
	
	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;
		
		$rs = MDL_Contest_List::getList($page);
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->display('contest_list.php');
	}

	public function ACT_entry()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();
		
		$contest_id = $this->path_option->getPathSection(2);

		$contest = new MDL_Contest($contest_id);
		
		$this->view->contest = $contest;
		$this->view->display('contest_entry.php');
	}
	
	public function ACT_signup()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();
		
		$contest_id = $this->path_option->getPathSection(2);
		$contest = new MDL_Contest($contest_id);
		
		$this->view->contest = $contest;
		$this->view->display('contest_signup.php');
	}

	public function ACT_dosignup()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();
		
		$contest_id = $this->path_option->getPathSection(2);
		$contest = new MDL_Contest($contest_id);
		
		$user_id = BFL_ACL::getInstance()->getUserID();
		$contest->signUp($user_id);
		
		$this->locator->redirect('contest/list');
	}
}