<?php
// parameters from URL
$location = $_GET['location'];
$type = $_GET['type'];

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `actuators_parameters`.`id`, `actuators_parameters`.`param`, `actuators_parameters`.`unit`, `actuators_parameters`.`data_type`, `actuators_parameters`.`icon_type`, `actuators_parameters`.`icon_url_na`, `actuators_parameters`.`icon_url_0`, `actuators_parameters`.`icon_url_1`, `actuators_parameters`.`value_0`, `actuators_parameters`.`value_1`, `actuators_parameters`.`type`, `actuators_parameters`.`comment`, `actuators`.`location`
FROM `actuators_parameters`, `actuators`
WHERE `actuators_parameters`.`id`=`actuators`.`id`
";

if ($location && $type)
	$sql .= "AND `actuators`.`location`='$location' AND `actuators_parameters`.`type`= '$type'\n";
elseif ($location && !$type)
	$sql .= "AND `actuators`.`location`='$location'\n";
elseif (!$location && $type)
	$sql .= "AND `actuators_parameters`.`type`='$type'\n";
$sql .= "ORDER BY `actuators_parameters`.`id` ASC, `actuators_parameters`.`param` ASC";

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
