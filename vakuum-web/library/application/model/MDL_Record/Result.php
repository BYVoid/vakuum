<?php
class MDL_Record_Result
{
	protected $result;

	public function __construct($record_meta)
	{
		if (isset($record_meta->result))
		{
			$result = $record_meta->result;

			$result = BFL_XML::XML2Array($result);
		}
		else
			$result = array();

		$this->result = $result;
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