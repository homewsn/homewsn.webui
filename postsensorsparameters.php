<?php
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
// unlike empty($var), if $var=0, isempty($var) returns FALSE
function isempty($var)
{
    return (!isset($var) || trim($var) === '');
}

// parameters from POST
$name = $_POST['name'];
if (empty($name))
	return httpStatus(400, 'Error: name parameter must be non-empty');
if ($name == 'id' || $name == 'param' || $name == 'unit' || $name == 'data_type')
	return httpStatus(400, 'Error: this name parameter is forbidden');
$value = $_POST['value'];
if (isempty($value) && ($name == 'icon_type' || $name == 'value_0' || $name == 'value_1' || $name == 'type'))
	return httpStatus(400, 'Error: value parameter must be non-empty');
$pk = $_POST['pk'];
if (empty($pk))
	return httpStatus(400, 'Error: pk parameter must be non-empty');
$ids = explode("-", $pk);
$id = $ids[0];
$param = $ids[1];
if (isempty($id) || isempty($param))
	return httpStatus(400, 'Error: pk parameter is invalid');


// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db);
if (!$link)
	return httpStatus(400, 'MySQL error: ' .mysqli_error($link));

$sql = "
UPDATE `sensors_parameters`
SET `sensors_parameters`.`$name`='$value'
WHERE `sensors_parameters`.`id`=$id AND `sensors_parameters`.`param`=$param
";

$result = mysqli_query($link, $sql);
if (!$result)
	return httpStatus(400, 'MySQL error: ' .mysqli_error($link));

httpStatus(200, 'Ok');
?>
