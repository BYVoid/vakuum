<?php
/**
 * Transmit through FTP
 *
 * @author BYVoid
 */
class MDL_Judger_Transmit_Share
{
	public static function uploadTask($ftpinfo,$task_name,$source,$src_name)
	{

	}
	
	private static function copy_file($dest,$src)
	{
		if (symlink($dest,$src) == false)
			throw new MDL_Exception_Judge_Send('copy_file');
	}
	
	public static function uploadTestdata($judger_info,$data_config)
	{
		$prob_name = $data_config['name'];
		$testdata_path = MDL_Config::getInstance()->getVar('judger_testdata').$prob_name.'/';

		$dest_path = $judger_info['path']['testdata'].$prob_name.'/';
		
		if (!file_exists($dest_path) || !is_dir($dest_path))
		{
			if (@mkdir($dest_path) === false)
			{
				throw new MDL_Exception_Judge_Send('dest_path');
			}
			chmod($dest_path,0755);
		}
		
		unset($data_config['id']);
		unset($data_config['title']);
		$xml = BFL_XML::Array2XML($data_config);
		
		if (file_put_contents($dest_path."config.xml",$xml) === false)
			throw new MDL_Exception_Judge_Send('write_file');
		
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