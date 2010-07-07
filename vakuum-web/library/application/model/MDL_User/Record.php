<?php

class MDL_User_Record
{
	protected $user = NULL;
	protected $records = NULL;
	protected $accepted_last_record = NULL;
	protected $accepted_problems = NULL;

	public function __construct($user)
	{
		$this->user = $user;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function getRecords()
	{
		if ($this->records === NULL)
		{
			$cache_key = 'user_records_'.$this->getUser()->getID();
			$cache = MDL_Cache::getInstance();

			if (!isset($cache->$cache_key))
			{
				$db = BFL_Database :: getInstance();
				$stmt = $db->factory('select `record_id` from '.DB_TABLE_RECORD.' where `record_user_id`=:user_id');
				$stmt->bindValue(':user_id', $this->getUser()->getID());
				$stmt->execute();

				$records = array();
				while ($rs = $stmt->fetch())
					$records[] = new MDL_Record($rs['record_id'],MDL_Record::GET_ALL);

				$cache->$cache_key = $records;
			}

			$records = $cache->$cache_key;
			$this->records = $records;
		}
		return $this->records;
	}

	public function getRecordsCount()
	{
		return count($this->getRecords());
	}

	public function getAcceptedRecords()
	{
		$records = array();
		foreach ($this->getRecords() as $record)
		{
			if ($record->getScore() == 1)
				$records[] = $record;
		}
		return $records;
	}

	public function getAcceptedLastRecords()
	{
		if ($this->accepted_last_record == NULL)
		{
			$problems_hash = array();
			foreach ($this->getAcceptedRecords() as $record)
				$problems_hash[$record->getProblem()->getID()] = $record;
			ksort($problems_hash);

			$records = array();
			foreach ($problems_hash as $problem_id => $record)
				$records[] = $record;

			$this->accepted_last_record = $records;
		}

		return $this->accepted_last_record;
	}

	public function getAcceptedProblems()
	{
		$problems = array();
		foreach ($this->getAcceptedLastRecords() as $record)
			$problems[] = $record->getProblem();

		return $problems;
	}

	public function getAcceptedProblemsCount()
	{
		return count($this->getAcceptedLastRecords());
	}

	public function getAcceptedRate()
	{
		if ($this->getRecordsCount() == 0)
			return 0;
		return $this->getAcceptedProblemsCount() / $this->getRecordsCount();
	}
}