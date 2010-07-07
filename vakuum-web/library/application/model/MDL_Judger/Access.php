<?php
class MDL_Judger_Access
{
	public static function getState($judger)
	{
		$url = $judger->getConfig()->getRemoteURL();
		$public_key = $judger->getConfig()->getRemoteKey();
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

	public static function sendRequest($judger, $task)
	{
		$judger_url = $judger->getConfig()->getRemoteURL();
		$public_key = $judger->getConfig()->getRemoteKey();
		$return_url = MDL_Config::getInstance()->getVar('judge_return_site') .
				MDL_Locator::getInstance()->getURL('judge_return');

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

	public static function stopJudge($task_name, $judger)
	{
		$client = new BFL_RemoteAccess_Client($judger->getConfig()->getRemoteURL(), $judger->getConfig()->getRemoteKey(), 1);
		$client->stopJudge($task_name);
	}

	public static function getPublicKey()
	{
		return BFL_RemoteAccess_Common::keyhash(MDL_Config::getInstance()->getVar('judge_return_key'));
	}
}