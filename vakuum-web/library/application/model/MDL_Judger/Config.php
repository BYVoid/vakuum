<?php
class MDL_Judger_Config
{
	protected $config = NULL;

	public function __construct($judger_config = '')
	{
		if ($judger_config != '')
			$this->config = BFL_XML::XML2Array($judger_config);
		else
		{
			$this->config = array
			(
				'url' => '',
				'public_key' => '',
				'upload' => 'ftp',
			);
		}
	}

	public function getRemoteURL()
	{
		return $this->config['url'];
	}

	public function getRemoteKey()
	{
		return $this->config['public_key'];
	}

	public function getUploadMethod()
	{
		return $this->config['upload'];
	}

	public function getTestdataPath($field = NULL)
	{
		if ($field == NULL)
			$field = $this->getUploadMethod();
		if (!isset($this->config[$field]))
			return NULL;
		return $this->config[$field]['path']['testdata'];
	}

	public function getTaskPath($field = NULL)
	{
		if ($field == NULL)
			$field = $this->getUploadMethod();
		if (!isset($this->config[$field]))
			return NULL;
		return $this->config[$field]['path']['task'];
	}

	public function getFTPAddress()
	{
		if (!isset($this->config['ftp']))
			return NULL;
		return $this->config['ftp']['address'];
	}

	public function getFTPUser()
	{
		if (!isset($this->config['ftp']))
			return NULL;
		return $this->config['ftp']['user'];
	}

	public function getFTPPassword()
	{
		if (!isset($this->config['ftp']))
			return NULL;
		return $this->config['ftp']['password'];
	}
}