<?php

class MDL_Contest_Score
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
			$records = $this->getMeta()->getVar('records');

			if ($records === false)
				$this->records = array();
			else
			{
				$records = explode(',',$records);

				foreach ($records as $record_id)
				{
					$this->records[] = new MDL_Record($record_id, MDL_Record::GET_NONE);
				}
			}
		}
		return $this->records;
	}

	/**
	 *
	 * @param int $prob_id
	 * @return MDL_Record
	 */
	public function getLastRecordWithProblem($problem)
	{
		$prob_id = $problem->getID();
		if (!isset($this->last_record[$prob_id]))
		{
			$ret = NULL;
			$records = $this->getRecords();
			foreach ($records as $record)
			{
				if ($record->getProblemID() == $prob_id)
					$ret = $record;
			}
			$this->last_record[$prob_id] = $ret;
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
}