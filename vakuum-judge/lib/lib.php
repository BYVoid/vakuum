<?php
require_once('BFL_RemoteAccess.php');
require_once('xml.php');
require_once('config.php');

function check_config()
{
	$path = Config::getInstance()->getVar('path');
	if (!file_exists($path['task']))
		return 'Task dir not exist';
	if (!file_exists($path['testdata']))
		return 'Testdata dir not exist';
	if (!file_exists($path['compiler'].'compiler'))
		return 'Compiler not exist';
	if (!file_exists($path['executor'].'executor'))
		return 'Executor not exist';
	if (!file_exists($path['checker'].'std_checker'))
		return 'Checker not exist';
	
	if (!is_writable($path['task']))
		return 'Task dir is not writable';
		
	return '';
}

function writelog($message)
{
	if (!DEBUG_MODE)
		return;
	if (is_array($message))
	{
		ob_start();
		print_r($message);
		$message = ob_get_clean();
	}
	static $fp;
	if ($fp == NULL)
		$fp = fopen("vakuum-judge-log.txt","w");
	fwrite($fp,$message."\n");
}

function logErrorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
	writelog('Error No: '.$errno);
	writelog('Error String: '.$errstr);
	writelog('Error File: '.$errfile);
	writelog('Error Line: '.$errline);
	return false;
}
