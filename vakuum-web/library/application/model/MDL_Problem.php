<?php
class MDL_Problem
{
	const GET_NONE = 0;
	const GET_NAMES = 1;
	const GET_ALL = 2;

	protected $prob_id;
	protected $prob_name;
	protected $prob_title;
	protected $prob_contents;
	protected $prob_meta;
	protected $prob_info;

	public function __construct($prob_identifier, $initial_get = self::GET_NONE)
	{
		$this->prob_id = $this->prob_name = $this->prob_title =
		$this->prob_meta = $this->prob_contents = $this->prob_info = NULL;

		if (is_numeric($prob_identifier))
		{
			$this->prob_id = (int) $prob_identifier;
		}
		else
		{
			$this->prob_name = $prob_identifier['name'];
			$this->getID();
		}

		if ($initial_get == self::GET_NAMES)
		{
			$this->getName();
			$this->getTitle();
		}
		else if ($initial_get == self::GET_ALL)
		{
			$this->getContents();
			$this->getInfo();
		}
	}

	public function getID()
	{
		if ($this->prob_id == NULL)
		{
			$prob_name = $this->getName();

			$db = BFL_Database :: getInstance();
			$stmt = $db->factory('select `prob_id`,`prob_title` from '.DB_TABLE_PROB.' WHERE `prob_name` = :prob_name');
			$stmt->bindParam(':prob_name', $prob_name);
			$stmt->execute();
			$rs = $stmt->fetch();

			if (empty($rs))
				throw new MDL_Exception_Problem(MDL_Exception_Problem::INVALID_PROB_NAME);

			$this->prob_id = $rs['prob_id'];
			$this->prob_title = $rs['prob_title'];
		}
		return $this->prob_id;
	}

	public function getName()
	{
		if ($this->prob_name == NULL)
			$this->getNamesByID();
		return $this->prob_name;
	}

	public function getTitle()
	{
		if ($this->prob_title == NULL)
			$this->getNamesByID();
		return $this->prob_title;
	}

	protected function getNamesByID()
	{
		$prob_id = $this->getID();

		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `prob_name`,`prob_title` from '.DB_TABLE_PROB.' WHERE `prob_id` = :prob_id');
		$stmt->bindParam(':prob_id', $prob_id);
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
			throw new MDL_Exception_Problem(MDL_Exception_Problem::INVALID_PROB_ID);

		$this->prob_name = $rs['prob_name'];
		$this->prob_title = $rs['prob_title'];
	}

	public function getContents()
	{
		if ($this->prob_contents == NULL)
		{
			$prob_id = $this->getID();

			$db = BFL_Database :: getInstance();
			$stmt = $db->factory('select * from '.DB_TABLE_PROB.' WHERE `prob_id` = :prob_id');
			$stmt->bindParam(':prob_id', $prob_id);
			$stmt->execute();
			$rs = $stmt->fetch();

			if (empty($rs))
				throw new MDL_Exception_Problem(MDL_Exception_Problem::INVALID_PROB_ID);

			$this->prob_name = $rs['prob_name'];
			$this->prob_title = $rs['prob_title'];
			$this->prob_contents = $rs['prob_content'];
		}

		return $this->prob_contents;
	}

	public function getProblemMeta()
	{
		if ($this->prob_meta == NULL)
		{
			$this->prob_meta = new MDL_Problem_Meta($this->getID());
		}
		return $this->prob_meta;
	}

	public function getInfo()
	{
		if ($this->prob_info == NULL)
		{
			$problem = $this->getProblemMeta()->getAll();
			$problem['data_config'] = BFL_XML::XML2Array($problem['data_config']);
			if (!isset($problem['data_config']['case'][0]))
				$problem['data_config']['case'] = array($problem['data_config']['case']);

			$this->prob_info = $problem;
		}

		return $this->prob_info;
	}
}