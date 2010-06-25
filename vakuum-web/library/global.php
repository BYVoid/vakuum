<?php
if (DEBUG)
	error_reporting(E_ALL|E_STRICT);
else
	error_reporting(0);

define('DB_TABLE_CONFIG','`' . DB_PREFIX . 'config' . '`');
define('DB_TABLE_USER','`' . DB_PREFIX . 'user' . '`');
define('DB_TABLE_USERMETA','`' . DB_PREFIX . 'usermeta' . '`');
define('DB_TABLE_PROB','`' . DB_PREFIX . 'prob' . '`');
define('DB_TABLE_PROBMETA','`' . DB_PREFIX . 'probmeta' . '`');
define('DB_TABLE_JUDGER','`' . DB_PREFIX . 'judger' . '`');
define('DB_TABLE_RECORD','`' . DB_PREFIX . 'record' . '`');
define('DB_TABLE_RECORDMETA','`' . DB_PREFIX . 'recordmeta' . '`');
define('DB_TABLE_CONTEST','`' . DB_PREFIX . 'contest' . '`');
define('DB_TABLE_CONTESTMETA','`' . DB_PREFIX . 'contestmeta' . '`');

if (get_magic_quotes_gpc())
{
	foreach($_GET as $k => $v)
		$_GET[$k] = stripslashes($v);
	foreach($_POST as $k => $v)
		$_POST[$k] = stripslashes($v);
	foreach($_REQUEST as $k => $v)
		$_REQUEST[$k] = stripslashes($v);
}

function getDBInfo()
{
	return array
	(
		'type' => DB_TYPE,
		'host' => DB_HOST,
		'name' => DB_NAME,
		'user' => DB_USER,
		'password' => DB_PASSWORD,
	);
}