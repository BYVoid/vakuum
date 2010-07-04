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
		if ($page === false)
			$page = 1;

		$list = new MDL_Contest_List($page);

		$this->view->list = $list;

		$this->view->display('contest/list.php');
	}

	public function ACT_entry()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();

		$contest_id = $this->path_option->getPathSection(2);
		$prob_alias = $this->path_option->getPathSection(3);

		$contest = new MDL_Contest($contest_id);
		$user = $this->acl->getUser();
		$contest_user = new MDL_Contest_User($contest, $user);

		$this->view->contest_user = $contest_user;
		$this->view->contest = $contest;

		if ($prob_alias === false)
		{
			$this->view->display('contest/entry.php');
		}
		else
		{
			if (!$contest_user->canViewProblem())
				$this->deny();

			$contest_user->setStart();
			$prob_id = $contest->getConfig()->getProbIDbyAlias($prob_alias);
			$problem = new MDL_Problem($prob_id,MDL_Problem::GET_ALL);

			$this->view->problem = $problem;
			$this->view->submit_url = $this->locator->getURL('contest/submit');
			$this->view->display('problem/single.php');
		}
	}

	public function ACT_rank()
	{
		$contest_id = $this->path_option->getPathSection(2);

		$contest = new MDL_Contest($contest_id);

		$this->view->contest = $contest;
		$this->view->display('contest/rank.php');
	}

	public function ACT_record()
	{
		$contest_id = $this->path_option->getVar('contest');
		$user_id = $this->path_option->getVar('user');
		$problem_id = $this->path_option->getVar('problem');

		$contest = new MDL_Contest($contest_id);
		$user = new MDL_User($user_id);
		$contest_user = new MDL_Contest_User($contest, $user);

		if ($problem_id != NULL)
		{
			$problem = new MDL_Problem($problem_id);
			$records = $contest_user->getRecordsWithProblem($problem);
		}
		else
		{
			$records = $contest_user->getRecords();
		}

		$this->view->list = $records;
		$this->view->info = array(
			'page_count' => 1,
			'current_page' => 1,
		);

		$this->view->display('record/list.php');
	}

	public function ACT_dosignup()
	{
		if (!$this->acl->check(array('general')))
			$this->deny();

		$contest_id = $this->path_option->getPathSection(2);
		$user = $this->acl->getUser();
		$contest_user = new MDL_Contest_User(new MDL_Contest($contest_id), $user);
		$contest_user->signUp();

		$this->locator->redirect('contest/entry',NULL,'/'.$contest_id);
	}

	public function ACT_submit()
	{
		if (!$this->acl->check('general'))
			$this->deny();

		if ($this->config->getVar('judge_allowed') != 1)
			$this->deny();

		$user = $this->acl->getUser();
		$contest_id = $_POST['contest_id'];
		$contest = new MDL_Contest($contest_id);
		$contest_user = new MDL_Contest_User($contest,$user);
		if (!$contest_user->canSubmit())
			$this->deny();

		$prob_id = $_POST['prob_id'];
		$language = $_POST['lang'];
		$source = file_get_contents($_FILES['source']['tmp_name']);

		$record_display = $contest->getConfig()->getRecordDisplay('during');
		$record_id = MDL_Judge_Single::submit($user->getID(),$prob_id,$language,$source,$record_display);

		$contest_user->addRecord($record_id);

		MDL_Judger_Process::processTaskQueue();

		$this->locator->redirect('record/detail',array(),'/'.$record_id);
	}
}