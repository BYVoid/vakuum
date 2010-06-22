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
		
		try
		{
			$rs = MDL_Record_List::getList($page);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'problem_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->display('record_list.php');
	}
	
	public function ACT_detail()
	{
		$record_id = $this->path_option->getPathSection(2);
		try
		{
			$record = MDL_Record_Detail::getInfo($record_id);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1]=='id')
				$this->notFound(array('specifier' => 'record'));
			else
				throw $e;
		}
		
		$this->view->record = $record;
		$this->view->display('record_single.php');
	}
	
	public function ACT_source()
	{
		$record_id = $this->path_option->getPathSection(2);
		try
		{
			$record = MDL_Record_Detail::getInfo($record_id);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1]=='id')
				$this->notFound(array('specifier' => 'record'));
			else
				throw $e;
		}
		
		$this->view->record = $record;
		$this->view->display('record_source.php');
	}
}
