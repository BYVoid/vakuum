<?php
/**
 * Submit a single record
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit
{
	public static function sendTask($judger_config,$task_name,$source,$src_name)
	{
		if ($judger_config['upload'] == 'share')
		{
			MDL_Judger_Transmit_Share::uploadTask($judger_config['share'],$task_name,$source,$src_name);
		}
		else if ($judger_config['upload'] == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTask($judger_config['ftp'],$task_name,$source,$src_name);
		}
		else
		{
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_UPLOAD_METHOD);
		}
	}
	
	public static function sendTestdata($judger_config,$data_config)
	{
		if ($judger_config['upload'] == 'share')
		{
			MDL_Judger_Transmit_Share::uploadTestdata($judger_config['share'],$data_config);
		}
		else if ($judger_config['upload'] == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTestdata($judger['ftp'],$data_config);
		}
		else
		{
			throw new MDL_Exception_Judge(MDL_Exception_Judge::INVALID_UPLOAD_METHOD);
		}
	}
}