<?php
class CTL_admin_record extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_record_list');
	}
	
	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		try
		{
			$rs = MDL_Record_List::getList($page,50);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'record_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->display('record_list.php');
	}
	
	public function ACT_rejudge()
	{
		$record_id = $this->path_option->getVar('record_id');
		if ($record_id !== false)
		{
			$rs = MDL_Record::exists($record_id);
			if (empty($rs))
			{
				$this->notFound(array('specifier' => 'record_id'));
			}
			if (!MDL_Record::completed($record_id))
			{
				$this->locator->redirect('admin_error_record_rejudge',array('specifier' => 'record_isrunning'));
			}
			MDL_Judge_Single::rejudgeSingle($record_id);
			$this->locator->redirect('record_detail',array(),'/'.$record_id);
		}
		$prob_id = $this->path_option->getVar('prob_id');
		if ($prob_id !== false)
		{
			try
			{
				MDL_Problem_Show::getProblemName($prob_id);
			}
			catch(MDL_Exception_Problem $e)
			{
				$this->notFound(array('specifier' => 'prob_id'));
			}
			MDL_Judge_Single::rejudgeProblem($prob_id);
			$this->locator->redirect('admin_problem_list');
		}
	}
	
	public function ACT_delete()
	{
		$record_id = (int)$this->path_option->getPathSection(2);
		if ($record_id == 0)
			$this->notFound(array('specifier' => 'record_id'));
		
		try
		{
			MDL_Record_Edit::delete($record_id);
		}
		catch(MDL_Exception_Record $e)
		{
			$desc = $e->getDescription();
			$request['specifier'] = $desc[2];
			$this->locator->redirect('admin_error_record_edit',$request);
		}
		$this->locator->redirect('admin_record_list');
	}
	
	public function ACT_stopjudge()
	{
		$record_id = (int)$this->path_option->getPathSection(2);
		if ($record_id == 0)
			$this->notFound(array('specifier' => 'record_id'));
		
		MDL_Judge_Single::stop($record_id);
		
		$this->locator->redirect('admin_record_list');
	}
}