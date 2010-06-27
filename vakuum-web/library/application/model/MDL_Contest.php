<?php

class MDL_Contest
{
	protected $contest_id;
	protected $contest_status;
	protected $contest_config;
	protected $cmeta;
	protected $sign_up_users = NULL;
	protected $user_score = array();
	protected $user_records = array();

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

		if (!isset($this->cmeta[$user_id]))
			$this->cmeta[$user_id] = new MDL_Contest_Meta($this->contest_id, $user_id);

		if ($this->cmeta[$user_id]->haveVar('sign_up'))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::SIGN_UP_ALREADY);

		$this->cmeta[$user_id]->setVar('sign_up', time());
	}

	public function checkSignUp($user_id)
	{
		if (!isset($this->cmeta[$user_id]))
			$this->cmeta[$user_id] = new MDL_Contest_Meta($this->contest_id, $user_id);

		return $this->cmeta[$user_id]->haveVar('sign_up');
	}

	public function checkContestPermission($user_id)
	{
		//檢査比賽時間
		if (!$this->contest_config->isDuringContest(time()))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_DURING_CONTEST_TIME);

		//檢査是否報名
		$user_id = BFL_ACL::getInstance()->getUserID();
		if (!$this->checkSignUp($user_id))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_SIGN_UP);

		return true;
	}

	/**
	 *
	 * @param int $user_id
	 * @return array(MDL_Record)
	 */
	public function getUserRecords($user_id)
	{
		if (!isset($this->user_records[$user_id]))
		{
			if (!isset($this->cmeta[$user_id]))
				$this->cmeta[$user_id] = new MDL_Contest_Meta($this->contest_id, $user_id);

			$records = $this->cmeta[$user_id]->getVar('records');

			if ($records === false)
				$this->user_records[$user_id] = array();
			else
			{
				$records = explode(',',$records);

				foreach ($records as $record_id)
				{
					$this->user_records[$user_id][] = new MDL_Record($record_id, MDL_Record::GET_NONE);
				}
			}
		}

		return $this->user_records[$user_id];
	}

	/**
	 *
	 * @param int $user_id
	 * @return MDL_Record
	 */
	public function getUserLastRecordWithProblem($user_id, $prob_id)
	{
		$ret = NULL;
		$records = $this->getUserRecords($user_id);
		foreach ($records as $record)
		{
			if ($record->getProblemID() == $prob_id)
				$ret = $record;
		}
		return $ret;
	}

	public function addRecord($user_id, $record_id)
	{
		if (!isset($this->cmeta[$user_id]))
			$this->cmeta[$user_id] = new MDL_Contest_Meta($this->contest_id, $user_id);

		$records = $this->getUserRecords($user_id);

		$record_ids = array();
		foreach ($records as $record)
			$record_ids[] = $record->getID();
		$record_ids[] = $record_id;

		$this->cmeta[$user_id]->setVar('records', implode(',', $record_ids));

		$this->user_last_record[$user_id] = new MDL_Record($record_id, MDL_Record::GET_NONE);
	}

	public function getSignUpUsers()
	{
		if ($this->sign_up_users == NULL)
		{
			$db = BFL_Database::getInstance();
			$stmt = $db->factory('select cmeta_user_id from '.DB_TABLE_CONTESTMETA.
					'where `cmeta_contest_id`=:contest_id and `cmeta_key`="sign_up" ');
			$stmt->bindParam(':contest_id', $this->contest_id);
			$stmt->execute();

			$this->sign_up_users = array();
			while ($user_item = $stmt->fetch())
			{
				$user_id = $user_item['cmeta_user_id'];
				$this->sign_up_users[] = new MDL_User($user_id, MDL_User::ID_USER_ID, MDL_User::GET_NONE);
			}
		}

		return $this->sign_up_users;
	}

	public function getUserScore($user_id)
	{
		$problems = $this->getConfig()->getProblems();
		$retval = 0;

		foreach ($problems as $problem)
		{
			$record = $this->getUserLastRecordWithProblem($user_id, $problem->getID());
			if ($record != NULL)
			{
				$retval += $record->getScore() * $problem->score;
			}
		}

		return $retval;
	}

	public function getPenaltyTime($user_id)
	{
		return 0;
	}
}