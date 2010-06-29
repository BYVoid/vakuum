<?php
class MDL_Preference
{
	public static function getPreferences()
	{
		$config = MDL_Config::getInstance();
		$preferences = $config->getAll();
		$preferences['register_form_restrict'] = unserialize($preferences['register_form_restrict']);
		return $preferences;
	}

	public static function editPreferences($preferences)
	{
		$config = MDL_Config::getInstance();
		$config->setVars($preferences);
	}
}