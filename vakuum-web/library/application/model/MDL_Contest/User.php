<?php

class MDL_Contest_User
{
	protected $contest;
	protected $user;
	protected $cmeta = NULL;
	protected $records = NULL;
	protected $last_record = array();
	protected $score = NULL;
	protected $score_problem = array();

	public function __construct($contest,$user)
	{
		$this->contest = $contest;
		$this->user = $user;
	}

	/**
	 * @return MDL_Contest
	 */
	public function getContest()
	{
		return $this->contest;
	}

	/**
	 * @return MDL_User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return MDL_Contest_Meta
	 */
	protected function getMeta()
	{
		if ($this->cmeta == NULL)
			$this->cmeta = new MDL_Contest_Meta($this->getContest()->getID(), $this->getUser()->getID());
		return $this->cmeta;
	}

	/**
	 *
	 * @param int $user_id
	 * @return array(MDL_Record)
	 */
	public function getRecords()
	{
		if ($this->records == NULL)
		{
			if (isset($this->getMeta()->records))
			{
				$records = explode(',',$this->getMeta()->records);
				foreach ($records as $record_id)
				{
					$this->records[] = new MDL_Record($record_id, MDL_Record::GET_NONE);
				}
			}
			else
				$this->records = array();
		}
		return $this->records;
	}

	/**
	 *
	 * @param MDL_Problem $problem
	 * @return array(MDL_Record)
	 */
	public function getRecordsWithProblem($problem)
	{
		$records_prob = array();
		foreach ($this->getRecords() as $record)
			if ($record->getProblem()->getID() == $problem->getID())
				$records_prob[] = $record;
		return $records_prob;
	}

	/**
	 *
	 * @param MDL_Problem $problem
	 * @return MDL_Record
	 */
	public function getLastRecordWithProblem($problem)
	{
		$prob_id = $problem->getID();
		if (!isset($this->last_record[$prob_id]))
		{
			$records = $this->getRecordsWithProblem($problem);
			if (count($records) > 0)
				$record = $records[count($records) - 1];
			else
				$record = NULL;
			$this->last_record[$prob_id] = $record;
		}
		return $this->last_record[$prob_id];
	}

	public function getScoreWithProblem($problem)
	{
		$prob_id = $problem->getID();
		if (!isset($this->score_problem[$prob_id]))
		{
			$record = $this->getLastRecordWithProblem($problem);
			if ($record != NULL)
				$this->score_problem[$prob_id] = $record->getScore() * $problem->score;
			else
				$this->score_problem[$prob_id] = 0;
		}
		return $this->score_problem[$prob_id];
	}

	public function getScore()
	{
		if ($this->score == NULL)
		{
			$retval = 0;

			foreach ($this->getContest()->getConfig()->getProblems() as $problem)
			{
				$score = $this->getScoreWithProblem($problem);
				$retval += $score;
			}

			$this->score = $retval;
		}
		return $this->score;
	}

	public function getPenaltyTimeWithProblem($problem)
	{
		return 0;
	}

	public function getPenaltyTime()
	{
		return 0;
	}

	public function signUp($check_sign_up_time = true)
	{
		if ($check_sign_up_time && !$this->getContest()->getConfig()->isDuringSignUp(time()))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_DURING_SIGN_UP_TIME);

		if ($this->checkSignUp())
			throw new MDL_Exception_Contest(MDL_Exception_Contest::SIGN_UP_ALREADY);

		$this->getMeta()->setVar('sign_up', time());
	}

	public function checkSignUp()
	{
		return $this->getMeta()->haveVar('sign_up');
	}

	public function checkContestPermission()
	{
		//檢査比賽時間
		if (!$this->getContest()->getConfig()->isDuringContest(time()))
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_DURING_CONTEST_TIME);

		//檢査是否報名
		if (!$this->checkSignUp())
			throw new MDL_Exception_Contest(MDL_Exception_Contest::NOT_SIGN_UP);

		return true;
	}

	public function addRecord($record_id)
	{
		$records = $this->getRecords();

		$record_ids = array();
		foreach ($records as $record)
			$record_ids[] = $record->getID();
		$record_ids[] = $record_id;

		$this->getMeta()->setVar('records', implode(',', $record_ids));

		$record = new MDL_Record($record_id);
		$this->last_record[$record->getProblem()->getID()] = $record;
	}
}