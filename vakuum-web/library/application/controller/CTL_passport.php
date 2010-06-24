<?php
require_once('CTL_Abstract/Controller.php');

class CTL_passport extends CTL_Abstract_Controller
{
	public function ACT_index()
	{
		$this->locator->redirect('passport_login');
	}
	
	public function ACT_login()
	{
		if (!$this->acl->check('guest'))
			$this->deny();
		$this->view->display('passport_login.php');
	}
	
	public function ACT_register()
	{
		if (!$this->acl->check('guest'))
			$this->deny();
		
		if (!$this->config->getVar('register_allowed'))
			$this->deny();
			
		$this->view->captcha = $this->config->getVar('register_captcha');
			
		$this->view->display('passport_register.php');
	}
	
	public function ACT_dologin()
	{
		if (!$this->acl->check('guest'))
			$this->deny();
		
		$user_info = array
		(
			'user_name'		   => $_POST['user_name'],
			'user_password'	   => $_POST['user_password'],
		);
		
		MDL_User_Auth::login($user_info);
		
		$this->locator->redirect('index');
	}
	
	public function ACT_dologout()
	{
		MDL_User_Auth::logout();
		$this->locator->redirect('passport_login');
	}
	
	public function ACT_doregister()
	{
		if (!$this->acl->check('guest'))
			$this->deny();
		if (!$this->config->getVar('register_allowed'))
			$this->deny();

		$user = array
		(
			'user_name'	=> $_POST['user_name'],
			'user_password' => $_POST['user_password'],
			'user_password_repeat' => $_POST['user_password_repeat'],
			'user_nickname'	=> $_POST['user_nickname'],
			'email'	=> $_POST['email'],
			'website' => $_POST['website'],
			'memo' => $_POST['memo'],
		);
		
		//Plugin: reCaptcha Validate Start----------------------------------------
		if ($this->config->getVar('captcha')==1 && method_exists('reCaptcha','validate'))
		{
			$validation = reCaptcha::validate();
			if ($validation !== true)
			{
				$request['specifier'] = $validation;
				$this->locator->redirect('error_register_failed',$request);
			}
		}
		//Plugin: reCaptcha Validate End----------------------------------------

		MDL_User_Edit::create($user);
		
		$this->locator->redirect('passport_login');
	}
	
	public function ACT_sendvalidation()
	{
		if (!$this->acl->check('unvalidated'))
			$this->deny();
		$user = BFL_Register::getVar('personal');
		MDL_User_Edit::sendValidationCode($user['user_name'],$user['email'],$user['validation_code']);
		
		$this->locator->redirect('user_space');
	}
	
	public function ACT_dovalidation()
	{
		$user_name = $this->path_option->getVar('user_name');
		$validation_code = $this->path_option->getVar('code');
		
		MDL_User_Edit::validate($user_name,$validation_code);
		
		$this->locator->redirect('index');
	}
}