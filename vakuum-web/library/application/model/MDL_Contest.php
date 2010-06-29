<?php

class MDL_Contest
{
	protected $contest_id;
	protected $contest_status = NULL;
	protected $contest_config = NULL;
	protected $contest_users = NULL;
	protected $rank = NULL;

	public function __construct($contest_id)
	{
		$this->contest_id = $contest_id;

		//$this->contest_status = $contest_status;
		//$this->contest_config = new MDL_Contest_Info($contest_config);
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
		if ($this->contest_config == NULL)
			$this->loadContest();
		return $this->contest_config;
	}

	public function getStatus()
	{
		if ($this->contest_status == NULL)
			$this->loadContest();
		return $this->contest_status;
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

	public function getContestUsers()
	{
		if ($this->contest_users == NULL)
		{
			$db = BFL_Database::getInstance();
			$stmt = $db->factory('select cmeta_user_id from '.DB_TABLE_CONTESTMETA.
					'where `cmeta_contest_id`=:contest_id and `cmeta_key`="sign_up" ');
			$stmt->bindParam(':contest_id', $this->contest_id);
			$stmt->execute();

			$this->contest_users = array();
			while ($user_item = $stmt->fetch())
			{
				$user_id = $user_item['cmeta_user_id'];
				$this->contest_users[] = new MDL_Contest_User($this,new MDL_User($user_id));
			}
		}

		return $this->contest_users;
	}

	public function getContestUsersCount()
	{
		return count($this->getContestUsers());
	}

	public function getRank($rank)
	{
		if ($this->rank == NULL)
			$this->rank = new MDL_Contest_Rank($this);
		return $this->rank->getRank($rank);
	}

	public function getRankDisplay()
	{
		if ($this->rank == NULL)
			$this->rank = new MDL_Contest_Rank($this);
		return $this->rank->getRankDisplay();
	}
}