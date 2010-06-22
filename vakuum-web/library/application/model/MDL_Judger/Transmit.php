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
		if ($judger['upload'] == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTask($judger['ftp'],$task_name,$source,$src_name);
		}
		else
		{
			throw new MDL_Exception_Judge_Send('upload_mothod');
		}
	}
	
	public static function sendTestdata($judger,$data_config)
	{
		if ($judger['upload'] == 'ftp')
		{
			MDL_Judger_Transmit_FTP::uploadTestdata($judger['ftp'],$data_config);
		}
		else
		{
			throw new MDL_Exception_Judge_Send('upload_mothod');
		}
	}
}