<?php

class MDL_User
{
	const ID_USER_ID = 0;
	const ID_USER_NAME = 1;

	const GET_NONE = 0;
	const GET_NAMES = 1;
	const GET_ALL = 2;

	protected $user_id = NULL;
	protected $user_name = NULL;
	protected $user_nickname = NULL;
	protected $user_password = NULL;
	protected $user_info = NULL;
	protected $user_meta = NULL;

	public function __construct($user_identidier, $idspec = self::ID_USER_ID,
			$initial_get = self::GET_NAMES, $addition = array())
	{
		if ($idspec == self::ID_USER_ID)
			$this->user_id = $user_identidier;
		else if ($idspec = self::ID_USER_NAME)
			$this->user_name = $user_identidier;
		else
			throw new MDL_Exception_User(MDL_Exception_User::INVALID_IDENTIFIER_SPEC);

		switch ($initial_get)
		{
			case self::GET_ALL:
				$this->getInfo();

			case self::GET_NAMES:
				$this->getID();
				$this->getName();

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
				case 'user_name':
					$this->user_name = $value;
					break;
				case 'user_nickname':
					$this->user_nickname = $value;
					break;
			}
		}
	}

	public function getID()
	{
		if ($this->user_id == NULL)
		{
			$db = BFL_Database :: getInstance();
			$stmt = $db->factory('select * from '.DB_TABLE_USER.' where `user_name`=:user_name');
			$stmt->bindValue(':user_name', $this->getName());
			$stmt->execute();
			$rs = $stmt->fetch();

			if (empty($rs))
				throw new MDL_Exception_User(MDL_Exception_User::INVALID_USER_NAME);

			$this->user_id = $rs['user_id'];
			$this->user_nickname = $rs['user_nickname'];
			$this->user_password = $rs['user_password'];
		}
		return $this->user_id;
	}

	protected function getNamesByID()
	{
		$db = BFL_Database :: getInstance();
		$stmt = $db->factory('select `user_name`,`user_nickname` from '.DB_TABLE_USER.' WHERE `user_id` = :user_id');
		$stmt->bindParam(':user_id', $this->getID());
		$stmt->execute();
		$rs = $stmt->fetch();

		if (empty($rs))
			throw new MDL_Exception_User(MDL_Exception_User::INVALID_USER_ID);

		$this->user_name = $rs['user_name'];
		$this->user_nickname = $rs['user_nickname'];
		$this->user_password = $rs['user_password'];
	}

	public function getName()
	{
		if ($this->user_name == NULL)
		{
			$this->getNamesByID();
		}
		return $this->user_name;
	}

	public function getNickname()
	{
		if ($this->user_nickname == NULL)
		{
			$this->getNamesByID();
		}
		return $this->user_nickname;
	}

	protected function getMeta()
	{
		if ($this->user_meta == NULL)
		{
			$this->user_meta = new MDL_User_Meta($this->getID());
		}
		return $this->user_meta;
	}

	public function getInfo()
	{
		if ($this->user_info == NULL)
		{
			$this->user_info = $this->getMeta()->getAll();
		}
		return $this->user_info;
	}
}