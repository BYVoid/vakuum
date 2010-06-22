<?php
require_once('CTL_Abstract/Controller.php');

/**
 * plugin Controller
 *
 * @author BYVoid
 */
class CTL_plugin extends CTL_Abstract_Controller
{
	public function ACT_request()
	{
		$plugin_name = $this->path_option->getPathSection(2);
		if (in_array($plugin_name,MDL_Plugin::$plugins))
		{
			$plugin = new $plugin_name;
			if (method_exists($plugin,'request'))
				$plugin->request();
		}
	}
}