<?php
class BFL_ACL
{
	protected static $_instance = NULL;
	/**
	 * getInstance
	 * @return BFL_ACL
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

	private function __clone(){}
	private function __construct()
	{
		session_start();
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
	
	public function setUserID($UserID)
	{
		$_SESSION[$this->prefix]['UserID'] = $UserID;
	}
	
	public function getUserID()
	{
		if (isset($_SESSION[$this->prefix]['UserID']))
			return $_SESSION[$this->prefix]['UserID'];
		return 0;
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

	public function allow($identity)
	{
		if (!is_array($identity))
			$identity = array($identity);
		foreach ($identity as $item)
		{
			if (in_array($item,$_SESSION[$this->prefix]['identity']))
				return false;
		}
		return true;
	}
	
	public function deny($identity)
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
}