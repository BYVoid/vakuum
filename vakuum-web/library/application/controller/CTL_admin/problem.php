<?php
require_once("Abstract.php");

class CTL_admin_problem extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_problem_list');
	}

	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		$rs = MDL_Problem_List::getList($page);

		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];

		$this->view->display('problem_list.php');
	}

	public function ACT_edit()
	{
		$prob_id = (int)$this->path_option->getPathSection(2);
		if ($prob_id != 0)
		{
			$rs = MDL_Problem_Show::getProblem($prob_id);

			$rs['action'] = 'edit';
		}
		else
		{
			$rs = array
			(
				'prob_id' => MDL_Problem_Edit::getNextProblemID(),
				'prob_title' => '',
				'prob_name' => '',
				'prob_content' => '',
				'display' => 0,
			);

			$rs['action'] = 'add';
		}

		$this->view->problem = $rs;
		$this->view->display('problem_edit.php');
	}

	public function ACT_data()
	{
		$prob_id = (int)$this->path_option->getPathSection(2);
		if ($prob_id == 0)
			$this->notFound();

		$prob_names = MDL_Problem_Show::getProblemName($prob_id);

		$this->view->data_config = MDL_Problem_Data::getDataConfig($prob_id,$prob_names['prob_name'],$prob_names['prob_title']);

		$this->view->display('problem_data.php');
	}

	public function ACT_verify()
	{
		$prob_id = $this->path_option->getPathSection(2);

		$prob_names = MDL_Problem_Show::getProblemName($prob_id);

		$data_config = MDL_Problem_Data::getDataConfig($prob_id,$prob_names['prob_name'],$prob_names['prob_title']);
		$verify_result = MDL_Problem_Dispatch::verify($data_config);

		$this->view->data_config = $data_config;
		$this->view->verify_result = $verify_result;
		$this->view->display('problem_verify.php');
	}

	public function ACT_dispatch()
	{
		$prob_id = (int)$this->path_option->getPathSection(2);
		if ($prob_id == 0)
			$this->notFound();

		$prob_names = MDL_Problem_Show::getProblemName($prob_id);

		$data_config = MDL_Problem_Data::getDataConfig($prob_id,$prob_names['prob_name'],$prob_names['prob_title']);
		$verify_result = MDL_Problem_Dispatch::verify($data_config);
		if ($verify_result['overall'] != '')
			$this->locator->redirect('admin_problem_verify',array(),'/'.$prob_id);
		$data_config['hash_code'] = $verify_result['hash_code'];

		$judgers = MDL_Problem_Dispatch::getJudgersTestdataVersion($data_config);

		$this->view->data_config = $data_config;
		$this->view->judgers = $judgers;
		$this->view->display('problem_dispatch.php');
	}

	public function ACT_doedit()
	{
		if ($_POST['data'] == 'problem')
		{
			//Do Problem Edit
			$problem = array
			(
				'prob_id' => $_POST['prob_id'],
				'prob_title' => $_POST['prob_title'],
				'prob_name' => $_POST['prob_name'],
				'prob_content' => $_POST['prob_content'],
				'display' => isset($_POST['display'])?$_POST['display']:0,
			);

			if ($_POST['action'] == 'add')
			{
				//Add New Problem
				MDL_Problem_Edit::add($problem);
				$this->locator->redirect('admin_problem_data',array(),'/'.$_POST['prob_id']);
			}
			else if ($_POST['action'] == 'edit')
			{
				if (isset($_POST['remove']) && $_POST['remove']==1)
					//Remove Problem
					MDL_Problem_Edit::remove($_POST['prob_id']);
				else
					//Edit Problem
					MDL_Problem_Edit::edit($problem);
				$this->locator->redirect('admin_problem_list');
			}
			else
				$this->deny();

		}
		else if ($_POST['data'] == 'data')
		{
			MDL_Problem_Data::edit($_POST);

			$this->locator->redirect('admin_problem_verify',array(),'/'.$_POST['id']);
		}
		else
			$this->deny();
	}

	public function ACT_dodispatch()
	{
		if (isset($_POST['ajax']))
			$ajax = true;
		//TODO: add non-ajax support
		if (!isset($_POST['prob_id']) || !isset($_POST['judger_id']))
			die('interface error');

		$prob_id = $_POST['prob_id'];
		$judger_id = $_POST['judger_id'];

		$prob_names = MDL_Problem_Show::getProblemName($prob_id);

		$data_config = MDL_Problem_Data::getDataConfig($prob_id,$prob_names['prob_name'],$prob_names['prob_title']);
		$verify_result = MDL_Problem_Dispatch::verify($data_config);
		if ($verify_result['overall'] != '')
			die('problem not verified');

		$rs = MDL_Problem_Dispatch::transmitTestdata($judger_id,$data_config);

		if ($rs['version'] == $data_config['version'] && $rs['hash_code'] == $verify_result['hash_code'])
		{
			$result['overall'] = "";
			if ($data_config['checker']['type'] == 'custom' && $data_config['checker']['custom']['language']!='')
			{
				if (!isset($rs['checker_compile']) || $rs['checker_compile']!=1)
					$result['overall'] = "checker_compile";
			}
		}
		else
			$result['overall'] = "verify";

		echo BFL_XML::Array2XML($result);
	}
}