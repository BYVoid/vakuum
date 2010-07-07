<?php
/**
 * Transmit through FTP
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit_Share
{
	public static function uploadTask($judger,$task_name,$source,$src_name)
	{
		$task_path = $judger->getConfig()->getTaskPath() .$task_name;

		$original_cwd = getcwd();
		if (@chdir($task_path) === false)
		{
			if (mkdir($task_path) === false)
				throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TASK_UPLOAD);
			if (chmod($task_path,0777) === false)
				throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TASK_UPLOAD);
			chdir($task_path);
		}

		if (file_put_contents($src_name,$source) === false)
			throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TASK_UPLOAD);

		chdir($original_cwd);
	}

	private static function copy_file($dest,$src)
	{
		if (symlink($dest,$src) == false)
			throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TESTDATA_UPLOAD);
	}

	public static function uploadTestdata($judger,$data_config)
	{
		$prob_name = $data_config['name'];
		$testdata_path = MDL_Config::getInstance()->getVar('judger_testdata').$prob_name.'/';

		$dest_path = $judger->getConfig()->getTestdataPath().$prob_name.'/';

		if (!file_exists($dest_path) || !is_dir($dest_path))
		{
			if (@mkdir($dest_path) === false)
			{
				throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TESTDATA_UPLOAD);
			}
			chmod($dest_path,0755);
		}

		unset($data_config['id']);
		unset($data_config['title']);
		$xml = BFL_XML::Array2XML($data_config);

		if (file_put_contents($dest_path."config.xml",$xml) === false)
			throw new MDL_Exception_Judge_Share(MDL_Exception_Judge_Share::TESTDATA_UPLOAD);

		if ($testdata_path == $dest_path)
			return;

		if ($data_config['checker']['type']=='custom')
		{
			//Upload checker
			$checker_source = $data_config['checker']['custom']['source'];
			$checker_file = $testdata_path.$checker_source;
			$this->copy_file($dest_path.$checker_source,$checker_file);
		}

		//Upload testdatas
		foreach($data_config['case'] as $item)
		{
			foreach(array('input','output') as $key)
			{
				$testdata_file = $testdata_path.$item[$key];
				$this->copy_file($dest_path.$item[$key],$testdata_file);
			}
		}
	}
}