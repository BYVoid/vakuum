<?php
class BFL_Serializer
{
	public static function transmitEncode($desc)
	{
		return base64_encode(gzdeflate(serialize($desc)));
	}
	
	public static function transmitDecode($desc)
	{
		return unserialize(gzinflate(base64_decode($desc)));
	}
}