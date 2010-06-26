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
		if ($page===false)
			$page = 1;

		$rs = MDL_Record_List::getList($page);

		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];

		$this->view->display('record/list.php');
	}

	public function ACT_detail()
	{
		$record_id = $this->path_option->getPathSection(2);

		$record = new MDL_Record($record_id);

		/* FIXME */
		if (!$record->getInfo()->getDisplay()->showInRecordList())
		{
			$this->deny();
		}

		$this->view->record = $record;
		$this->view->display('record/single.php');
	}

	public function ACT_source()
	{
		$record_id = $this->path_option->getPathSection(2);

		$record = MDL_Record::getRecordSource($record_id);

		$this->view->record = $record;
		$this->view->display('record/source.php');
	}
}
