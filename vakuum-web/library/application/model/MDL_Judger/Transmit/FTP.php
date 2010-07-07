<?php
/**
 * Transmit through FTP
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit_FTP
{
	const PORT = 21;
	const TIMEOUT = 5;

	private static function connect($judger)
	{
		//Connect to the judger server
		$judger_config = $judger->getConfig();
		$port = self::PORT;

		$pftp = ftp_connectct($judger_config->getAddress() ,$port,self::TIMEOUT);
		if ($pftp === false)
		{
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::CONNECT);
		}
		$login_result = ftp_login($pftp, $judger_config->getUser(), $judger_config->getPassword());
		if ($login_result === false)
		{
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::LOGIN);
		}
		return $pftp;
	}

	public static function uploadTask($judger,$task_name,$source,$src_name)
	{
		//Create a temp file to store the source file and get a file pointer $fp
		$temp_file = tempnam(sys_get_temp_dir(), 'Vakuum');
		file_put_contents($temp_file,$source);

		$pftp = self::connect($judger);

		//Create task dir and set permission
		if (ftp_chdir($pftp, $judger->getConfig()->getTaskPath()) === false)
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TASK_UPLOAD);
		if (@ftp_chdir($pftp,$task_name) === false)
		{
			if (ftp_mkdir($pftp,$task_name) === false)
				throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TASK_UPLOAD);
			if (ftp_chmod($pftp,0777,$task_name) === false)
				throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TASK_UPLOAD);
			ftp_chdir($pftp,$task_name);
		}

		//Upload the source file
		if (ftp_put($pftp, $src_name, $temp_file, FTP_BINARY) === false)
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TASK_UPLOAD);

		//Close the file and connection
		ftp_close($pftp);
	}

	public static function uploadTestdata($judger,$data_config)
	{
		$prob_name = $data_config['name'];
		$testdata_path = MDL_Config::getInstance()->getVar('judger_testdata').$prob_name.'/';

		$temp_file = tempnam(sys_get_temp_dir(), 'Vakuum');
		unset($data_config['id']);
		unset($data_config['title']);
		$xml = BFL_XML::Array2XML($data_config);
		file_put_contents($temp_file,$xml);

		$pftp = self::connect($judger);

		if (ftp_chdir($pftp, $judger->getConfig()->getTestdataPath()) === false)
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TESTDATA_UPLOAD);

		if (@ftp_chdir($pftp,$prob_name) === false)
		{
			ftp_mkdir($pftp,$prob_name);
			ftp_chmod($pftp,0755,$prob_name);
			ftp_chdir($pftp,$prob_name);
		}

		//Upload config.xml
		if (ftp_put($pftp, 'config.xml', $temp_file, FTP_BINARY) === false)
			throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TESTDATA_UPLOAD);

		if ($data_config['checker']['type']=='custom')
		{
			//Upload checker
			$checker_source = $data_config['checker']['custom']['source'];
			$checker_file = $testdata_path.$checker_source;
			if (ftp_put($pftp, $checker_source, $checker_file, FTP_BINARY) === false)
				throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TESTDATA_UPLOAD);
		}

		//Upload testdatas
		foreach($data_config['case'] as $item)
		{
			foreach(array('input','output') as $key)
			{
				$testdata_file = $testdata_path. $item[$key];
				if (ftp_put($pftp, $item[$key], $testdata_file, FTP_BINARY) === false)
					throw new MDL_Exception_Judge_FTP(MDL_Exception_Judge_FTP::TESTDATA_UPLOAD);
			}
		}

		ftp_close($pftp);
	}
}