<?php
/*
* Copyright (c) 2018 Vladimir Alemasov
* All rights reserved
*
* This program and the accompanying materials are distributed under 
* the terms of GNU General Public License version 2 
* as published by the Free Software Foundation.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*/

// http://stackoverflow.com/questions/3258634/php-how-to-send-http-response-code
function httpStatus($httpStatusCode, $httpStatusMsg)
{
	$phpSapiName = substr(php_sapi_name(), 0, 3);
	if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm')
		header('Status: ' .$httpStatusCode .' ' .$httpStatusMsg);
	else
	{
		$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
		header($protocol .' ' .$httpStatusCode .' ' .$httpStatusMsg);
	}
}

// parameters from POST
$name = $_POST['name'];
if (!$name)
	return httpStatus(400, 'name parameter must be non-empty');
if ($name == 'id' || $name == 'ip')
	return httpStatus(400, 'this name parameter is forbidden');
$value = $_POST['value'];
if (!$value)
	return httpStatus(400, 'value parameter must be non-empty');
$pk = $_POST['pk'];
if (!$pk)
	return httpStatus(400, 'pk parameter must be non-empty');

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db);
if (!$link)
	return httpStatus(400, 'Error: ' .mysqli_error($link));

$sql = "
UPDATE `devices`
SET `devices`.`$name`='$value'
WHERE `devices`.`id`='$pk'
";

$result = mysqli_query($link, $sql);
if (!$result)
	return httpStatus(400, 'Error: ' .mysqli_error($link));

httpStatus(200, 'Ok');
?>
