<?php
/**
 * load plugins
 *
 * @author BYVoid
 */
class MDL_Plugin
{
	public static $plugins=array();
	
	public static function load_plugins($plugins_path)
	{
		$dh=opendir($plugins_path);
		while ($file=readdir($dh))
		{
			if($file!="." && $file!="..") 
			{
				$plugin_file=$plugins_path.$file.'/';
				if(is_dir($plugin_file))
				{
					$interface = $plugin_file.'interface.php';
					if (file_exists($interface))
					{
						self::$plugins[]= strtolower($file);
						BFL_Register::setVar('plugin_file',$plugin_file);
						include_once($interface);
					}
				}
			}
		}
		closedir($dh);
	}
}