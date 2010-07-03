<?php
class MDL_ACL
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return MDL_ACL
	 */
	public static function getInstance()
	{
		if (NULL === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	protected $prefix;
	protected $null_user;

	private function __clone(){}
	private function __construct()
	{
		session_start();
		$this->null_user = new MDL_User(0, MDL_User::ID_USER_ID, MDL_User::GET_NONE, array
		(
			'user_name' => 'guest',
			'user_nickName' => 'Guest',
		));
	}

	/**
	 * Initialize the ACL class
	 * @param string $prefix A prefix on session to differ from other application
	 * @param string $default Default identity of guest
	 */
	public function initialize($prefix,$default)
	{
		$this->prefix = $prefix;
		if (!isset($_SESSION[$prefix]))
		{
			$_SESSION[$prefix] = array();
			$this->setIdentity($default);
		}
	}

	public function resetSession()
	{
		unset($_SESSION[$this->prefix]);
	}

	public function setUser($user)
	{
		$_SESSION[$this->prefix]['user'] = $user;
	}

	/**
	 * @return MDL_User
	 */
	public function getUser()
	{
		if (isset($_SESSION[$this->prefix]['user']))
			return $_SESSION[$this->prefix]['user'];
		return $this->null_user;
	}

	public function setIdentity($identity)
	{
		$identity = explode(',',$identity);

		$_SESSION[$this->prefix]['identity'] = $identity;
	}

	public function getIdentity()
	{
		return $_SESSION[$this->prefix]['identity'];
	}

	public function check($identity)
	{
		if (!is_array($identity))
			$identity = array($identity);
		foreach ($identity as $item)
		{
			if (in_array($item,$_SESSION[$this->prefix]['identity']))
				return true;
		}
		return false;
	}

	public function allowAdmin()
	{
		if ($this->check('administrator'))
		{
			if (DEBUG)
			{
				if (!BFL_Register::haveVar('warning_adminaccess'))
				{
					BFL_Register::setVar('warning_adminaccess',true);
					echo 'Warning: This page is not allowed to access by general users.';
				}
			}
			return true;
		}
		return false;
	}
}