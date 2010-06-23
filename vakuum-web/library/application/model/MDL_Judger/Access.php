<?php
class MDL_Judger_Access
{
	public static function getState($judger)
	{
		$judger_config = $judger['judger_config'];
		$url = $judger_config['url'];
		$public_key = $judger_config['public_key'];
		$client = new BFL_RemoteAccess_Client($url,$public_key);
		$client->__throwException(true);
		try
		{
			$state = $client->getState();
		}
		catch (Exception $e)
		{
			$state = $e->getMessage();
		}
		return $state;
	}
	
	public static function sendRequest($judger_url,$public_key,$task,$return_url)
	{
		$task = array
		(
			'prob_name' => $task['prob_name'],
			'task_name' => $task['task_name'],
			'source_file' => $task['src_name'],
			'language' => $task['language'],
			'return_url' => "{$return_url}?record_id={$task['record_id']}",
			'public_key' => self::getPublicKey(),
		);
		$client = new BFL_RemoteAccess_Client($judger_url,$public_key,1);
		$client->judge($task);
	}
	
	public static function stopJudge($task_name,$judger)
	{
		$client = new BFL_RemoteAccess_Client($judger['url'],$judger['public_key'],1);
		$client->stopJudge($task_name);
	}
	
	public static function getPublicKey()
	{
		return BFL_RemoteAccess_Common::keyhash(MDL_Config::getInstance()->getVar('judge_return_key'));
	}
}