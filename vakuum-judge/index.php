<?php
define('DEBUG_MODE',true);

require_once('lib/lib.php');
error_reporting(E_ALL | E_STRICT);
set_error_handler("logErrorHandler",E_ALL | E_STRICT);

$config_check_result = check_config();
$private_key = Config::getInstance()->getVar('private_key');

set_time_limit(0);
ignore_user_abort(true);


if ($config_check_result != '')
{
	writelog('Config error');
}

$server = new BFL_RemoteAccess_Server($private_key);
$server->bindFunction('getState','action_getState');
$server->bindFunction('judge','action_judge');
$server->bindFunction('stopJudge','action_stopJudge');
$server->bindFunction('getTestdataVersion','action_getTestdataVersion');
$server->bindFunction('updateTestdata','action_updateTestdata');

if (!$server->listen())
{
	include ('module/console/console.php');
}

function action_getState()
{
	global $config_check_result;
	if ($config_check_result != '')
		return 'config error: '.$config_check_result;
	return 'ready';
}

function action_judge($task)
{
	include('module/judge/judge.php');
	judge($task);
}

function action_stopJudge($task_name)
{
	include('module/judge/judge.php');
	stopJudge($task_name);
}

function action_getTestdataVersion($prob_name)
{
	include('module/data/data.php');
	return getTestdataVersion($prob_name);
}

function action_updateTestdata($prob_name)
{
	include('module/data/data.php');
	return updateTestdata($prob_name);
}