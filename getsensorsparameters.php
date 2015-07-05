<?php
// parameters from URL
$location = $_GET['location'];
$type = $_GET['type'];

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `sensors_parameters`.`id`, `sensors_parameters`.`param`, `sensors_parameters`.`unit`, `sensors_parameters`.`data_type`, `sensors_parameters`.`icon_type`, `sensors_parameters`.`icon_url_na`, `sensors_parameters`.`icon_url_0`, `sensors_parameters`.`icon_url_1`, `sensors_parameters`.`value_0`, `sensors_parameters`.`value_1`, `sensors_parameters`.`type`, `sensors_parameters`.`comment`, `sensors`.`location`
FROM `sensors_parameters`, `sensors`
WHERE `sensors_parameters`.`id` = `sensors`.`id`
";

if ($location && $type)
	$sql .= "AND `sensors`.`location`='$location' AND `sensors_parameters`.`type`= '$type'\n";
elseif ($location && !$type)
	$sql .= "AND `sensors`.`location`='$location'\n";
elseif (!$location && $type)
	$sql .= "AND `sensors_parameters`.`type`='$type'\n";
$sql .= "ORDER BY `sensors_parameters`.`id` ASC, `sensors_parameters`.`param` ASC";

$result = mysqli_query($link, $sql) or die('Error ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
	extract($row);
	$rows[] = "{\"id\": \"$id\", \"param\": \"$param\", \"unit\": \"$unit\", \"data_type\": \"$data_type\", \"icon_type\": \"$icon_type\", \"icon_url_na\": \"$icon_url_na\", \"icon_url_0\": \"$icon_url_0\", \"icon_url_1\": \"$icon_url_1\", \"value_0\": \"$value_0\", \"value_1\": \"$value_1\", \"location\": \"$location\", \"type\": \"$type\", \"comment\": \"$comment\"}";
}

// output to the browser
header('Content-Type: text/javascript; charset=UTF-8');
echo "[\n" .join(",\n", $rows) ."\n]";
?>
