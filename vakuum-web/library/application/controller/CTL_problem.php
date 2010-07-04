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
		if ($page === false)
			$page = 1;

		$list = new MDL_Problem_List($page);

		$this->view->list = $list;

		$this->view->display('problem/list.php');
	}

	public function SAC_otherAction()
	{
		$identifier = $this->path_option->getPathSection(1);

		if (is_numeric($identifier))
		{
			$prob_id = $identifier;
			try
			{
				$problem = new MDL_Problem($prob_id);
				$prob_name = $problem->getName();
			}
			catch (MDL_Exception_Problem $e)
			{
				if ($e->testTopDesc(MDL_Exception_Problem::INVALID_PROB_ID))
				{
					/* $identifier作爲prob_id未找到對應記錄，作爲prob_name再次査找 */
					$prob_name = $identifier;
				}
				else
					throw $e;
			}

			if ($prob_name != $prob_id)
			{
				/* 重定向到$prob_name路徑頁，如果$prob_name和$prob_id不同 */
				$this->locator->redirect('problem',array(),'/'.$prob_name);
			}
		}

		$prob_name = $identifier;

		/* $identifier作爲prob_name査找 */
		$problem = new MDL_Problem(array('name' => $prob_name), MDL_Problem::GET_ALL);
		if (!$problem->getProblemMeta()->display || !$problem->getProblemMeta()->verified)
			$this->deny();

		$this->view->problem = $problem;
		$this->view->submit_url = $this->locator->getURL('judge/submit');
		$this->view->display('problem/single.php');
	}
}