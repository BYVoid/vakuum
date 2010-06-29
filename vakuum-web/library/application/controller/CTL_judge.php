<?php
require_once('CTL_Abstract/Controller.php');

/**
 * judge Controller
 *
 * @author BYVoid
 */
class CTL_judge extends CTL_Abstract_Controller
{
	public function ACT_submit()
	{
		if (!$this->acl->check('general'))
			$this->deny();

		if ($this->config->getVar('judge_allowed') != 1)
			$this->deny();

		set_time_limit(0);
		ignore_user_abort(1);

		$user_id = $this->acl->getUser()->getID();
		$prob_id = $_POST['prob_id'];
		$prob_name = $_POST['prob_name'];
		$lang = $_POST['lang'];
		$source = file_get_contents($_FILES['source']['tmp_name']);

		$record_id = MDL_Judge_Single::submit($user_id,$prob_id,$lang,$source);
		MDL_Judger_Process::processTaskQueue();

		$this->locator->redirect('record_detail',array(),'/'.$record_id);
	}

	public function writeRecord($rs)
	{
		$info = $rs['info'];
		$info['record_id'] = $_GET['record_id'];
		switch ($rs['type'])
		{
			case 'compile':
				MDL_Judge_Record::recordCompile($info);
				break;
			case 'execute':
				MDL_Judge_Record::recordExecute($info);
				break;
			case 'complete':
				MDL_Judge_Record::recordComplete($info);
				break;
			default:
		}
	}

	public function ACT_return()
	{
		$server = new BFL_RemoteAccess_Server($this->config->getVar('judge_return_key'));
		$server->bindFunction('writeRecord',array($this,'writeRecord'));
		$server->listen();

	}

	private function fail($desc)
	{
		switch ($desc)
		{
			case 'source_encode':
			case 'source_length':
			default:
				$this->locator->redirect('error_judge_failed',array('specifier' => $desc));
		}
	}
}