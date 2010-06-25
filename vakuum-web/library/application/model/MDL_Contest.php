<?php

class MDL_Contest
{
	public function __toString()
	{
		return serialize($this);
	}
	
	public function __sleep()
	{
		return array('contest_id','contest_status','contest_config');
	}
	
	protected $contest_id;
	protected $contest_status;
	protected $contest_config;
	
	public function __construct($contest_id, $contest_config = '', $contest_status = '')
	{
		$this->contest_id = $contest_id;
		if ($contest_config == '')
			$this->loadContest();
		else
		{
			$this->contest_status = $contest_status;
			$this->contest_config = new MDL_Contest_Info($contest_config);
		}
	}
	
	public function getID()
	{
		return $this->contest_id;
	}
	
	/**
	 * @return MDL_Contest_Info
	 */
	public function getConfig()
	{
		return $this->contest_config;
	}

	protected function loadContest()
	{
		$db = BFL_Database::getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_CONTEST.' where `contest_id`=:contest_id');
		$stmt->bindParam(':contest_id', $this->contest_id);
		$stmt->execute();
		$contest = $stmt->fetch();
		
		if (empty($contest))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::INVALID_CONTEST_ID);
		
		$this->contest_status = $contest['contest_status'];
		$this->contest_config = new MDL_Contest_Info($contest['contest_config']);
	}
	
	public function signUp($user_id, $check_sign_up_time = true)
	{
		if ($check_sign_up_time && !$this->contest_config->isDuringSignUp(time()))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_DURING_SIGN_UP_TIME);
		
		$cmeta = new MDL_Contest_Meta($this->contest_id, $user_id);
		if ($cmeta->haveVar("sign_up"))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::SIGN_UP_ALREADY);
		$cmeta->setVar("sign_up", time());
	}
}