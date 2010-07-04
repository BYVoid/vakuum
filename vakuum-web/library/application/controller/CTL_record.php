<?php
require_once('CTL_Abstract/Controller.php');

/**
 * record Controller
 *
 * @author BYVoid
 */
class CTL_record extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('record_list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page === false)
			$page = 1;

		$list = new MDL_Record_List($page);

		$this->view->list = $list;

		$this->view->display('record/list.php');
	}

	public function ACT_detail()
	{
		$record_id = $this->path_option->getPathSection(2);

		$record = new MDL_Record($record_id);

		$this->view->record = $record;
		$this->view->display('record/single.php');
	}

	public function ACT_source()
	{
		$record_id = $this->path_option->getPathSection(2);

		$record = new MDL_Record($record_id);
		if (!$record->canViewSource())
			$this->deny();

		$this->view->record = $record;
		$this->view->display('record/source.php');
	}
}
