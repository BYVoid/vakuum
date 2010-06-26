<?php
require_once('CTL_Abstract/Controller.php');

class CTL_contest extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('contest/list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		$rs = MDL_Contest_List::getList($page);

		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];

		$this->view->display('contest/list.php');
	}

	public function ACT_entry()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();

		$contest_id = $this->path_option->getPathSection(2);
		$prob_alias = $this->path_option->getPathSection(3);

		$contest = new MDL_Contest($contest_id);

		$user_id = BFL_ACL::getInstance()->getUserID();
		$contest->checkContestPermission($user_id);

		$this->view->contest = $contest;

		if ($prob_alias === false)
		{
			$this->view->display('contest/entry.php');
		}
		else
		{
			$prob_id = $contest->getConfig()->getProbIDbyAlias($prob_alias);

			$problem = new MDL_Problem($prob_id,MDL_Problem::GET_ALL);

			$this->view->problem = $problem;
			$this->view->submit_url = $this->locator->getURL('contest/submit');
			$this->view->display('problem/single.php');
		}
	}

	public function ACT_rank()
	{
		//TODO permission

		$contest_id = $this->path_option->getPathSection(2);

		$contest = new MDL_Contest($contest_id);

		$this->view->contest = $contest;

		$this->view->display('contest/rank.php');

	}


	public function ACT_signup()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();

		$contest_id = $this->path_option->getPathSection(2);
		$contest = new MDL_Contest($contest_id);

		$this->view->contest = $contest;
		$this->view->display('contest/signup.php');
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

	public function ACT_submit()
	{
		if (!$this->acl->check('general'))
			$this->deny();

		if ($this->config->getVar('judge_allowed') != 1)
			$this->deny();


		$user_id = BFL_ACL::getInstance()->getUserID();
		$contest_id = $_POST['contest_id'];
		$contest = new MDL_Contest($contest_id);
		$contest->checkContestPermission($user_id);

		$prob_id = $_POST['prob_id'];
		$language = $_POST['lang'];
		$source = file_get_contents($_FILES['source']['tmp_name']);

		$record_id = MDL_Judge_Single::submit($user_id,$prob_id,$language,$source);

		$contest->addRecord($user_id,$record_id);

		MDL_Judger_Process::processTaskQueue();

		$this->locator->redirect('record/detail',array(),'/'.$record_id);
	}
}