<?php
class MDL_Record
{
	const GET_NONE = 0;
	const GET_ITEM = 1;
	const GET_ALL = 2;

	protected $record_id = NULL;
	protected $problem = NULL;
	protected $user = NULL;
	protected $judger = NULL;
	protected $info = NULL;

	public function __construct($record_id, $initial_get = self::GET_NONE, $addition = array())
	{
		$this->record_id = $record_id;

		switch ($initial_get)
		{
			case self::GET_ALL:
				$this->getInfo();

			case self::GET_ITEM:
				$this->initializeRecord();

			case self::GET_NONE:
				break;

			default:
				throw new MDL_Exception_User(MDL_Exception_User::INVALID_INITIAL_GET);
		}

		foreach ($addition as $key => $value)
		{
			switch ($key)
			{
				case 'user_id':
					$this->user = new MDL_User($value);
					break;
				case 'prob_id':
					$this->problem = new MDL_Problem($value);
					break;
				case 'judger_id':
					$this->judger = new MDL_Judger($value);
					break;
			}
		}
	}

	protected function initializeRecord()
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_RECORD.' where `record_id`=:record_id');
		$stmt->bindValue(':record_id', $this->getID());
		$stmt->execute();
		$record = $stmt->fetch();

		if (empty($record))
			throw new MDL_Exception_Record(MDL_Exception_Record::INVALID_RECORD_ID);

		$this->problem = new MDL_Problem($record['record_prob_id']);
		$this->user = new MDL_User($record['record_user_id']);
		$this->judger = new MDL_Judger($record['record_judger_id']);
	}

	public function getID()
	{
		if ($this->record_id == NULL)
			throw new MDL_Exception_Record(MDL_Exception_Record::INVALID_RECORD_ID);
		return $this->record_id;
	}

	/**
	 * @return MDL_Problem
	 */
	public function getProblem()
	{
		if ($this->problem == NULL)
			$this->initializeRecord();
		return $this->problem;
	}

	/**
	 * @return MDL_User
	 */
	public function getUser()
	{
		if ($this->user == NULL)
			$this->initializeRecord();
		return $this->user;
	}

	/**
	 * @return MDL_Judger
	 */
	public function getJudger()
	{
		if ($this->judger == NULL)
			$this->initializeRecord();
		return $this->judger;
	}

	public function getURL()
	{
		return MDL_Locator::getInstance()->getURL('record/detail/'.$this->getID());
	}

	/**
	 * @return MDL_Record_Info
	 */
	public function getInfo()
	{
		if ($this->info == NULL)
			$this->info = new MDL_Record_Info($this->getID());
		return $this->info;
	}

	public function getTaskName()
	{
		return 'vkm_'.$this->getID();
	}

	public function getSource()
	{
		return $this->getInfo()->source;
	}

	public function getSubmitTime()
	{
		return $this->getInfo()->submit_time;
	}

	public function getScore()
	{
		return $this->getInfo()->score;
	}

	public function getDisplay()
	{
		return $this->getInfo()->getDisplay();
	}

	public function getStatus()
	{
		return $this->getInfo()->status;
	}

	public function getResultText()
	{
		return $this->getInfo()->result_text;
	}

	public function canViewSource($user = NULL)
	{
		if ($this->getDisplay()->source)
			return true;

		if (MDL_ACL::getInstance()->allowAdmin())
			return true;

		if ($user == NULL)
			MDL_ACL::getInstance()->getUser();

		if ($this->getUser()->getID() == $user->getID())
			return true;
	}

	public function canView($key)
	{
		if ($this->getDisplay()->$key)
			return true;

		if (MDL_ACL::getInstance()->allowAdmin())
			return true;
	}

	public function judgeStopped()
	{
		$status = $this->getInfo()->getRecordMeta()->status;
		return $status == MDL_Judge_Record::STATUS_STOPPED;
	}
}