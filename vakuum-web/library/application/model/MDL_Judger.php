<?php
/**
 * judger
 *
 * @author BYVoid
 */
class MDL_Judger
{
	protected $judger_id = NULL;
	protected $judger_name = NULL;
	protected $judger_priority = NULL;
	protected $judger_enabled = NULL;
	protected $judger_available = NULL;
	protected $judger_count = NULL;
	protected $judger_config = NULL;

	const GET_NONE = 0;
	const GET_ALL = 1;

	public function __construct($judger_id, $initial_get = self::GET_NONE, $addition = array())
	{
		$this->judger_id = (int) $judger_id;

		if ($this->judger_id == 0)
		{
			$this->initializeNew();
			return;
		}

		switch ($initial_get)
		{
			case self::GET_ALL:
				$this->initializeJudger();

			case self::GET_NONE:
				break;

			default:
				throw new MDL_Exception_User(MDL_Exception_Judger::INVALID_INITIAL_GET);
		}

		foreach ($addition as $key => $value)
		{
			if ($key == 'judger_config')
				$this->judger_config = new MDL_Judger_Config($value);
			else
				$this->$key = $value;
		}
	}

	public function getID()
	{
		return $this->judger_id;
	}

	public function getName()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return $this->judger_name;
	}

	public function getPriority()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return $this->judger_priority;
	}

	public function getJudgeCount()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return $this->judger_count;
	}

	public function isEnabled()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return (bool) $this->judger_enabled;
	}

	public function isAvailable()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return (bool) $this->judger_available;
	}

	public function getConfig()
	{
		if ($this->judger_name == NULL)
			$this->initializeJudger();
		return $this->judger_config;
	}

	private function initializeJudger()
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select * from '.DB_TABLE_JUDGER . ' where `judger_id`=:judger_id');
		$stmt->bindValue(':judger_id', $this->getID());
		$stmt->execute();
		$judger = $stmt->fetch();

		$this->judger_available = $judger['judger_available'];
		$this->judger_count = $judger['judger_count'];
		$this->judger_enabled = $judger['judger_enabled'];
		$this->judger_priority = $judger['judger_priority'];
		$this->judger_name = $judger['judger_name'];
		$this->judger_config = new MDL_Judger_Config($judger['judger_config']);
	}

	private function initializeNew()
	{
		$this->judger_name = 'New Judger';
		$this->judger_available = false;
		$this->judger_enabled = true;
		$this->judger_count = 0;
		$this->judger_priority = 0;
		$this->judger_config = new MDL_Judger_Config();
	}

	public function lock()
	{
		$this->turn(0);
	}

	public function unlock()
	{
		$this->turn(1);
	}

	private function turn($judger_available)
	{
		$db = BFL_Database :: getInstance();
		$meta = array('`judger_available` = :judger_available');
		if ($judger_available == 0)
			$meta[] = '`judger_count` = `judger_count` + 1';
		$stmt = $db->update(DB_TABLE_JUDGER , $meta ,'where `judger_id`=:judger_id');
		$stmt->bindValue(':judger_available', $judger_available);
		$stmt->bindValue(':judger_id', $this->getID());
		$stmt->execute();
	}
}