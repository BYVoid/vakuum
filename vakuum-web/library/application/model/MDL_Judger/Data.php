<?php
/**
 * List all judgers
 *
 * @author BYVoid
 */
class MDL_Judger_Data
{
	public static function getTestdataVersion($judger_url,$public_key,$prob_name)
	{
		$client = new BFL_RemoteAccess_Client($judger_url,$public_key);
		return $client->getTestdataVersion($prob_name);
	}
	
	public static function updateTestdata($judger_url,$public_key,$prob_name)
	{
		$client = new BFL_RemoteAccess_Client($judger_url,$public_key);
		return $client->updateTestdata($prob_name);
	}

}