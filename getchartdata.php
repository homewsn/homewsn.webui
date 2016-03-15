<?php
// unlike empty($var), if $var=0, isempty($var) returns FALSE
function isempty($var)
{
    return (!isset($var) || trim($var) === '');
}

// parameters from URL
$id = $_GET['id'];
if (isempty($id))
	die('id parameter must be non-empty');
if ($id && !preg_match('/^[0-9]+$/', $id))
	die("Invalid id parameter: $id");

$param = $_GET['param'];
if (isempty($param))
	die('param parameter must be non-empty');
if ($param && !preg_match('/^[0-9]+$/', $param))
	die("Invalid param parameter: $param");

$data_type = $_GET['data_type'];
if (!$data_type)
	die('data_type parameter must be non-empty');
if (strcasecmp($data_type, 'long') != 0 && strcasecmp($data_type, 'float') != 0)
	die("Invalid data_type parameter: $data_type");

$start = $_GET['start'];
if ($start && !preg_match('/^[0-9]+$/', $start))
	die("Invalid start parameter: $start");
 
$end = $_GET['end'];
if ($end && !preg_match('/^[0-9]+$/', $end))
	die("Invalid end parameter: $end");

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error: ' .mysqli_error($link));
// set UTC time
mysqli_query($link, "SET time_zone = '+00:00'");

if (!$start)
{
	// timestamp of the first record in the database
	$sql = "
	SELECT unix_timestamp(`time`) * 1000 AS datetime
	FROM `data_long`
	WHERE (`id` = $id) AND (`param` = $param)
	ORDER BY `time` ASC
	LIMIT 1
	";

	$result = mysqli_query($link, $sql) or die('Error: ' .mysqli_error($link));
	$row = mysqli_fetch_assoc($result);
	extract($row);
	$start = $datetime;
}

if (!$end)
{
	// current time
	$end = time() * 1000;
}

// set some utility variables
$range = $end - $start;
$startTime = gmstrftime('%Y-%m-%d %H:%M:%S', $start / 1000);
$endTime = gmstrftime('%Y-%m-%d %H:%M:%S', $end / 1000);

// find the right table
if ($range < 172800000 /* 2 * 24 * 3600 * 1000 */)
{
	// 2 days range loads minute data
	if (strcasecmp($data_type, 'long') == 0)
		$table = 'data_long';
	else
		$table = 'data_float';
	$value = 'value';
}
elseif ($range < 2678400000 /* 31 * 24 * 3600 * 1000 */)
{
	// 31 days range loads hourly data
	if (strcasecmp($data_type, 'long') == 0)
		$table = 'data_long_hour';
	else
		$table = 'data_float_hour';
	$value = 'value_avg';
}
elseif ($range < 40176000000 /* 15 * 31 * 24 * 3600 * 1000 */)
{
	// 15 months range loads daily data
	if (strcasecmp($data_type, 'long') == 0)
		$table = 'data_long_day';
	else
		$table = 'data_float_day';
	$value = 'value_avg';
}
else
{
	// greater range loads monthly data
	if (strcasecmp($data_type, 'long') == 0)
		$table = 'data_long_month';
	else
		$table = 'data_float_month';
	$value = 'value_avg';
}

// select records from the right table
$sql = "
SELECT unix_timestamp(`time`) * 1000 AS datetime, `$value` AS value_avg
FROM `$table`
WHERE (`time` BETWEEN '$startTime' AND '$endTime') AND (`id` = $id) AND (`param` = $param)
ORDER BY `id` ASC , `param` ASC , `time` ASC
LIMIT 0, 5000
";

$result = mysqli_query($link, $sql) or die('Error: ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
  extract($row);
  $rows[] = "[$datetime, $value_avg]";
}

// last record table
if (strcasecmp($data_type, 'long') == 0)
	$table_last_record = 'data_long';
else
	$table_last_record = 'data_float';

// find the last record in the right table
$sql = "
SELECT unix_timestamp(`time`) * 1000 AS datetime, `value` AS value_avg
FROM `$table_last_record`
WHERE (`id` = $id) AND (`param` = $param)
ORDER BY `time` DESC
LIMIT 1
";

$result = mysqli_query($link, $sql) or die('Error: ' .mysqli_error($link));
$last = array();
$row = mysqli_fetch_assoc($result);
extract($row);
if (!isempty($value_avg))
{
	// add the last record if it exists
	$last[] = "[$datetime, $value_avg]";
	array_push($rows, $last[0]);
}

// output to the browser
header('Content-Type: text/javascript');
echo "[\n" . join(",\n", $rows) ."\n]";
?>