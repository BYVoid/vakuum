<?php
require_once("Abstract.php");

class CTL_admin_record extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_record_list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page === false)
			$page = 1;

		$list = new MDL_Record_List($page);

		$this->view->list = $list;

		$this->view->display('record_list.php');
	}

	public function ACT_rejudge()
	{
		$record_id = $this->path_option->getVar('record_id');
		if ($record_id !== false)
		{
			$record = new MDL_Record($record_id);
			if (!$record->judgeStopped())
				throw new MDL_Exception_Judge(MDL_Exception_Judge::ALREADY_RUNNING);

			MDL_Judge_Single::rejudgeSingle($record_id);
			$this->locator->redirect('record_detail',array(),'/'.$record_id);
		}

		$prob_id = $this->path_option->getVar('prob_id');
		if ($prob_id !== false)
		{
			MDL_Judge_Single::rejudgeProblem(new MDL_Problem($prob_id));
			$this->locator->redirect('admin_problem_list');
		}
	}

	public function ACT_delete()
	{
		$record_id = (int)$this->path_option->getPathSection(2);
		if ($record_id == 0)
			$this->notFound(array('specifier' => 'record_id'));

		MDL_Record_Edit::delete($record_id);

		$this->locator->redirect('admin_record_list');
	}

	public function ACT_stopjudge()
	{
		$record_id = $this->path_option->getPathSection(2);

		MDL_Judge_Single::stop($record_id);

		$this->locator->redirect('admin_record_list');
	}
}
