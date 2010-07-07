<?php
require_once('CTL_Abstract/Controller.php');

/**
 * judger Controller
 *
 * @author BYVoid
 */
class CTL_judger extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('judger/list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page === false)
			$page = 1;

		$rs = new MDL_Judger_List($page);

		$this->view->list = $rs;

		$this->view->display('judger/list.php');
	}

	public function ACT_process()
	{
		MDL_Judger_Process::processTaskQueue();
	}
}
