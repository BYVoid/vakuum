<?php
class MDL_Record_Info
{
	public function __toString()
	{
		return serialize($this);
	}

	public function __sleep()
	{
		return array();
	}

	private static $m_initialized = false;
	private static function mInitialize()
	{
		self::$m_initialized = true;
		self::$property_list = array
		(
			'language',	'status',	'result_text',	'submit_time',	'memory',
			'time',	'source', 'score',	'display',	'result',
		);
	}

	protected static $property_list;

	protected $record_id;
	private $rmeta;
	private $display;
	private $result;

	public function __construct($record_id)
	{
		if (!self::$m_initialized)
			self::mInitialize();
		$this->record_id = $record_id;
	}

	public function __get($property)
	{
		//if (!in_array($property, self::$property_list))
			//throw new MDL_Exception(MDL_Exception::UNDEFINED);

		if ($property == 'language')
			$property = 'lang';

		else if ($property == 'source_length')
			return strlen($this->source);

		return $this->getRecordMeta()->getVar($property);
	}

	/**
	 * @return MDL_Record_Meta
	 */
	protected function getRecordMeta()
	{
		if (empty($this->rmeta))
			$this->rmeta = new MDL_Record_Meta($this->record_id);
		return $this->rmeta;
	}

	/**
	 * @return MDL_Record_DisplayConfig
	 */
	public function getDisplay()
	{
		if (empty($this->display))
			$this->display = new MDL_Record_DisplayConfig($this->getRecordMeta()->getVar('display'));
		return $this->display;
	}

	public function getResult()
	{
		if (empty($this->result))
			$this->result = new MDL_Record_Result($this->getRecordMeta()->getVar('result'));
		return $this->result;
	}
}