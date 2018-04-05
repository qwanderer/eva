<?php

if($db_conf['driver'] == "mysql"){
	$link = mysql_connect($db_conf['host'], $db_conf['user'], $db_conf['pass']);
	if (!$link) { die('Could not connect: ' . mysql_error()); }
	mysql_select_db($db_conf['db_name'], $link);
}elseif($db_conf['driver'] == "mysqli"){
	$link = mysqli_connect($db_conf['host'], $db_conf['user'], $db_conf['pass'], $db_conf['db_name']);
	if (!$link) { die('Could not connect: ' . mysql_error()); }
}else{
	die("Unknown database driver");
}

require_once(CORE_PATH."DB_".$db_conf['driver'].".php");
$DB =& load_class("DB_".$db_conf['driver'], "core", $link);
