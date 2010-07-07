<?php
/**
 * Submit a single record
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit
{
	public static function sendTask($judger,$task_name,$source,$src_name)
	{
		$judger_config = $judger->getConfig();
		if ($judger_config->getUploadMethod() == 'share')
		{
			MDL_Judger_Transmit_Share::uploadTask($judger ,$task_name,$source,$src_name);
		}
		else if ($judger_config->getUploadMethod() == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTask($judger ,$task_name,$source,$src_name);
		}
		else
		{
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_UPLOAD_METHOD);
		}
	}

	public static function sendTestdata($judger,$data_config)
	{
		$judger_config = $judger->getConfig();
		if ($judger_config->getUploadMethod() == 'share')
		{
			MDL_Judger_Transmit_Share::uploadTestdata($judger ,$data_config);
		}
		else if ($judger_config->getUploadMethod() == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTestdata($judger ,$data_config);
		}
		else
		{
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_UPLOAD_METHOD);
		}
	}
}