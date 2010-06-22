<?php
require('recaptchalib.php');
class reCaptcha
{
	private static $private_key,$public_key;
	private static $mailhide_public_key = "01MpGWxmiH9wJG7YK7ygMFIg==";
	private static $mailhide_private_key = "A76E3556855929AFF1F9B5C514ABCC93";
	
	/**
	 * Initialize
	 */
	public static function initialize()
	{
		$config = MDL_Config::getInstance();
		self::$private_key = $config->getVar('plugin_ReCaptcha_private_key');
		self::$public_key = $config->getVar('plugin_ReCaptcha_public_key');
	}

	/**
	 * 
	 */
	public static function showValidate()
	{
		return recaptcha_get_html(self::$public_key, '' ,true);
	}
	
	/**
	 * 
	 * @param string $email_address
	 * @return string HTML email address
	 */
	public static function showEmail($email_address)
	{
		return recaptcha_mailhide_html(self::$mailhide_public_key, self::$mailhide_private_key, $email_address);
	}
	
	/**
	 * @return boolean or string True if pass valadation
	 */
	public static function validate()
	{
		if ($_POST["recaptcha_response_field"])
		{
			$resp = recaptcha_check_answer(self::$private_key,$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			
			if ($resp->is_valid)
			{
				return true;
			}
			else
			{
				return $resp->error;
			}
		}
		return 'no validation post';
	}
}

reCaptcha::initialize();