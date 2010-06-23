<?php
class MDL_Record_DisplayConfig
{
	const SHOW_IN_RECORD_LIST   = 1;
	const SHOW_COMPILE_RESULT   = 2;
	const SHOW_RUN_RESULT       = 4;
	const SHOW_CASE_RESULT      = 8;
	const SHOW_CODE_TO_OWNER    = 16;
	const SHOW_CODE_TO_PUBLIC   = 32;
	
	private $permission, $admin_privilege;
	
	static $initialized = false, $administrator;
	
	public function initailize()
	{
		self::$initialized = true;
		self::$administrator = BFL_ACL::getInstance()->check('administrator'); 
	}
	
	public function __construct($permission = 0, $admin_privilege = 0)
	{
		if (!self::$initialized)
			self::initailize();
		$this->permission = $permission;
		$this->admin_privilege = $admin_privilege && self::$administrator;
	}
	
	public function getValue()
	{
		return $this->permission;
	}
	
	/* Test functions */
	
	public function showInRecordList()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_IN_RECORD_LIST;
	}
	
	public function showCompileResult()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_COMPILE_RESULT;
	}
	
	public function showRunResult()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_RUN_RESULT;
	}
	
	public function showCaseResult()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_CASE_RESULT;
	}
	
	public function showCodeToOwner()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_CODE_TO_OWNER;
	}
	
	public function showCodeToPublic()
	{
		if ($this->admin_privilege)
			return true;
		return $this->permission & self::SHOW_CODE_TO_PUBLIC;
	}
	
	/* Set functions */
	
	public function setShowInRecordList($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_IN_RECORD_LIST;
		else
			$this->permission &= ~self::SHOW_IN_RECORD_LIST;
	}
	
	public function setShowCompileResult($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_COMPILE_RESULT;
		else
			$this->permission &= ~self::SHOW_COMPILE_RESULT;
	}
	
	public function setShowRunResult($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_RUN_RESULT;
		else
			$this->permission &= ~self::SHOW_RUN_RESULT;
	}
	
	public function setShowCaseResult($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_CASE_RESULT;
		else
			$this->permission &= ~self::SHOW_CASE_RESULT;
	}
	
	public function setShowCodeToOwner($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_CODE_TO_OWNER;
		else
			$this->permission &= ~self::SHOW_CODE_TO_OWNER;
	}
	
	public function setShowCodeToPublic($bl = true)
	{
		if ($bl)
			$this->permission |= self::SHOW_CODE_TO_PUBLIC;
		else
			$this->permission &= ~self::SHOW_CODE_TO_PUBLIC;
	}
}