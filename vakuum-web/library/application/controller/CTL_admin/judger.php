<?php
class CTL_admin_judger extends CTL_admin_Abstract
{
	public function ACT_index()
	{
		$this->locator->redirect('admin_judger_list');
	}
	
	public function ACT_list()
	{
		$page = $this->path_option->getVar('page');
		if ($page===false)
			$page = 1;

		try
		{
			$rs = MDL_Judger_List::getList($page);
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			if ($desc[1] == 'page')
				$this->notFound(array('specifier' => 'user_list_page'));
			else
				throw $e;
		}
		
		$this->view->list = $rs['list'];
		$this->view->info = $rs['info'];
		
		$this->view->server_key = MDL_Judger_Access::getPublicKey();
		
		$this->view->display('judger_list.php');
	}
	
	public function ACT_edit()
	{
		$judger_id = (int)$this->path_option->getPathSection(2);
			
		$judger = MDL_Judger_Edit::getDefault();
			
		if ($judger_id != 0)
		{
			try
			{
				$rs = MDL_Judger::getJudger($judger_id);
			}
			catch(MDL_Exception $e)
			{
				$desc = $e->getDescription();
				if ($desc[0] == 'judger_id')
					$this->notFound(array('specifier' => 'judger'));
				else
					throw $e;
			}
			
			$judger = BFL_General::arrayMerge($judger,$rs);
			
			$judger['action'] = 'edit';
		}
		else
		{
			$judger['action'] = 'add';
		}
		
		$this->view->judger = $judger;
		$this->view->display('judger_edit.php');
	}
	
	public function ACT_doedit()
	{
		$action = $_POST['action'];
		$judger = array
		(
			'judger_id' => $_POST['judger_id'],
			'judger_name' => $_POST['judger_name'],
			'judger_priority' => $_POST['judger_priority'],
			'judger_enabled' => (isset($_POST['judger_enabled'])&&($_POST['judger_enabled'] == 1))?1:0,
			'judger_config' => array
			(
				'url' => $_POST['url'],
				'public_key' => $_POST['public_key'],
				'upload'=> $_POST['upload'],
			),
		);
		
		if ($_POST['upload'] == 'ftp')
			$judger['judger_config']['ftp'] = $_POST['ftp'];
		else if ($_POST['upload'] == 'share')
			$judger['judger_config']['share'] = $_POST['share'];
		
		try
		{
			if ($_POST['action'] == 'add')
			{
				MDL_Judger_Edit::add($judger);
			}
			else if ($_POST['action'] == 'edit')
			{
				if (isset($_POST['remove']) && $_POST['remove']==1)
					MDL_Judger_Edit::remove($_POST['judger_id']);
				else
					MDL_Judger_Edit::edit($judger);
			}
			else
				$this->deny();
			
			$this->locator->redirect('admin_judger_list');
		}
		catch(MDL_Exception $e)
		{
			$desc = $e->getDescription();
			$request['specifier'] = $desc[0];
			$this->locator->redirect('admin_error_judger_edit',$request);
		}
	}
	
	public function ACT_force()
	{
		$judger_id = (int)$this->path_option->getPathSection(2);
		
		MDL_Judger_Edit::forceAvailable($judger_id);
			
		$this->locator->redirect('admin_judger_list');
	}
	
	public function ACT_connect()
	{
		$judger_id = (int)$this->path_option->getPathSection(2);
			
		if ($judger_id != 0)
		{
			try
			{
				$judger = MDL_Judger::getJudger($judger_id);
			}
			catch(MDL_Exception $e)
			{
				$desc = $e->getDescription();
				if ($desc[0] == 'judger_id')
					$this->notFound(array('specifier' => 'judger'));
				else
					throw $e;
			}
			
			$state = MDL_Judger_Access::getState($judger);
		}
		else
		{
			$this->notFound(array('specifier' => 'judger'));
		}
		
		$this->view->state = $state;
		$this->view->display('judger_connect.php');
	}
}