<?php
class MDL_Record_Result
{
	protected $result;

	public function __construct($result)
	{
		$this->result = BFL_XML::XML2Array($result);
	}

	public function getCompile()
	{
		if (isset($this->result['compile']))
			return $this->result['compile'];
		return false;
	}

	public function getExecute()
	{
		if (isset($this->result['execute']))
			return $this->result['execute']['case'];
		return false;
	}
}