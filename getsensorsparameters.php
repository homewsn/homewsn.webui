<?php
// parameters from URL
$location = $_GET['location'];
$type = $_GET['type'];

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `parameters`.`id`, `parameters`.`param`, `parameters`.`unit`, `parameters`.`data_type`, `parameters`.`icon_type`, `parameters`.`icon_url_na`, `parameters`.`icon_url_0`, `parameters`.`icon_url_1`, `parameters`.`value_0`, `parameters`.`value_1`, `parameters`.`type`, `parameters`.`comment`, `sensors`.`location`
FROM `parameters`, `sensors`
WHERE `parameters`.`id` = `sensors`.`id`
";

if ($location && $type)
	$sql .= "AND `sensors`.`location`='$location' AND `parameters`.`type`= '$type'\n";
elseif ($location && !$type)
	$sql .= "AND `sensors`.`location`='$location'\n";
elseif (!$location && $type)
	$sql .= "AND `parameters`.`type`='$type'\n";
$sql .= "ORDER BY `parameters`.`id` ASC, `parameters`.`param` ASC";

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
