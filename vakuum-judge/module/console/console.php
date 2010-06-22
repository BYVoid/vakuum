<?php
if ($config_check_result != '')
	die($config_check_result);
$public_key = BFL_RemoteAccess_Common::keyhash($private_key);
echo $public_key;

/*	include('module/judge/judge.php');
	$task = array
	(
		'task_name' => 'vkm_2',
		'prob_name' => 'ARAN',
		'source_file' => 'vkm_2.cpp',
		'language' => 'cpp',
		'return_url' => '',
		'public_key' => '',
	);
	judge($task);*/