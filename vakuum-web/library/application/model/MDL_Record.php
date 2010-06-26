<?php
class MDL_Record
{
	const GET_NONE = 0;
	const GET_ITEM = 1;
	const GET_ALL = 2;

	protected $record_id = NULL;
	protected $prob_id = NULL;
	protected $user_id = NULL;
	protected $judger_id = NULL;
	protected $info = NULL;

	public function __construct($record_id, $initial_get = self::GET_ITEM, $addition = array())
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
					$this->user_id = $value;
					break;
				case 'prob_id':
					$this->prob_id = $value;
					break;
				case 'judger_id':
					$this->judger_id = $value;
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

		$this->prob_id = $record['record_prob_id'];
		$this->user_id = $record['record_user_id'];
		$this->judger_id = $record['record_judger_id'];
	}

	public function getID()
	{
		if ($this->record_id == NULL)
			throw new MDL_Exception_Record(MDL_Exception_Record::INVALID_RECORD_ID);
		return $this->record_id;
	}

	public function getProblemID()
	{
		if ($this->prob_id == NULL)
			$this->initializeRecord();
		return $this->prob_id;
	}

	public function getUserID()
	{
		if ($this->user_id == NULL)
			$this->initializeRecord();
		return $this->user_id;
	}

	public function getJudgerID()
	{
		return $this->judger_id;
	}

	/**
	 * @return MDL_Record_Info
	 */
	protected function getInfo()
	{
		if ($this->info == NULL)
			$this->info = new MDL_Record_Info($this->getID());
		return $this->info;
	}

	public function getProblem()
	{
		return new MDL_Problem($this->getProblemID(), MDL_Problem::GET_NONE);
	}

	public function getUser()
	{
		return new MDL_User($this->getUserID(), MDL_User::ID_USER_ID, MDL_User::GET_NONE);
	}

	public function getJudger()
	{

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

	public static function completed($record_id)
	{
		$rmeta = new MDL_Record_Meta($record_id);
		$status = (int)$rmeta->getVar('status');
		return $status == MDL_Judge_Record::STATUS_STOPPED;
	}

	public static function getTaskName($record_id)
	{
		return 'vkm_'.$record_id;
	}

	public static function getTask()
	{
		//find records whose record_judger_id = 0

		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `record_id`,`record_prob_id` from '.DB_TABLE_RECORD.
			' where `record_judger_id` = 0 order by `record_id` asc');

		$stmt->execute();
		$task = $stmt->fetch();

		if (!$task)
		{
			return array();
		}

		$record_meta = new MDL_Record_Meta($task['record_id']);
		$task['language'] = $record_meta->getVar('lang');
		$task['source'] = $record_meta->getVar('source');
		$submit_time = $record_meta->getVar('submit_time');
		$task['task_name'] = self::getTaskName($task['record_id'],$submit_time);

		$prob_names = MDL_Problem_Show::getProblemName($task['record_prob_id']);
		$task['prob_name'] = $prob_names['prob_name'];

		unset($task['record_prob_id']);

		return $task;
	}

	public static function getSrcname($task_name,$lang)
	{
		switch ($lang)
		{
			case 'c':
				$suffix = '.c';
				break;
			case 'cpp':
				$suffix = '.cpp';
				break;
			case 'pas':
				$suffix = '.pas';
				break;
		}
		return $task_name . $suffix;
	}
}