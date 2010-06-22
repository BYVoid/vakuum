<?php
class gravatar
{
	private static $size = 100;
	private static $default = "";
	
	/**
	 * 
	 * @param int $size
	 */
	public static function setDefaultSize($size)
	{
		$size = (int)$size;
		if ($size > 0)
			self::$size = $size;
	}
	
	/**
	 * 
	 * @param string $default
	 */
	public static function setDefaultImage($default)
	{
		self::$default = urlencode($default);
	}
	
	/**
	 * 
	 * @param string $email
	 * @param int $size
	 * @param string $default
	 * @return string HTML image
	 */
	public static function showImage($email,$size=0,$default="")
	{
		$url = self::getGravatarURL($email,$size,$default);
		return "<img src=\"{$url}\" alt=\"Gravatar\"/>";
	}
	
	/**
	 * 
	 * @param string $email
	 * @param int $size
	 * @param string $default
	 * @return string URL
	 */
	public static function getGravatarURL($email,$size=0,$default="")
	{
		if ($size == 0)
			$size = self::$size;
			
		if ($default == "")
			$default = self::$default;
		
		$url = "http://www.gravatar.com/avatar/".md5($email)."?s={$size}&d={$default}";
		return $url;
	}
}
