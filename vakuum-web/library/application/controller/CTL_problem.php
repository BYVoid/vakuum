<?php
require_once('CTL_Abstract/Controller.php');

/**
 * problem Controller
 *
 * @author BYVoid
 */
class CTL_problem extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		//Show problem list
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;
		
		try
		{
			$list = MDL_Problem_List::getList($page,true);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'problem_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $list['list'];
		$this->view->info = $list['info'];
		
		$this->view->display('problem_list.php');
	}
	
	public function SAC_otherAction()
	{
		$identifier = $this->path_option->getPathSection(1);

		$problem_show = new MDL_Problem_Show();

		try
		{
			if (is_numeric($identifier))
			{
				$prob_names = $problem_show->getProblemName($identifier);
				$prob_name = $prob_names['prob_name'];
				$this->locator->redirect('problem_single',array(),'/'.$prob_name);
			}
			else
				$problem = $problem_show->getProblemByName($identifier);
		}
		catch(MDL_Exception_Problem $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'id')
				$this->notFound(array('specifier' => 'problem'));
			else
				throw $e;
		}
		
		$this->view->problem = $problem;
		$this->view->display('problem_single.php');
	}
}