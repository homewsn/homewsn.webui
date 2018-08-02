<?php
/*
* Copyright (c) 2015, 2018 Vladimir Alemasov
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

// parameters from URL
$location = $_GET['location'];
$type = $_GET['type'];

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `parameters`.`id`, `parameters`.`param`, `parameters`.`unit`, `parameters`.`data_type`, `parameters`.`icon_type`, `parameters`.`icon_url_na`, `parameters`.`icon_url_0`, `parameters`.`icon_url_1`, `parameters`.`value_0`, `parameters`.`value_1`, `parameters`.`type`, `parameters`.`comment`, `devices`.`location`
FROM `parameters`, `devices`
WHERE `parameters`.`id` = `devices`.`id` AND `parameters`.`param_type`='sensor'
";

if ($location && $type)
	$sql .= "AND `devices`.`location`='$location' AND `parameters`.`type`= '$type'\n";
elseif ($location && !$type)
	$sql .= "AND `devices`.`location`='$location'\n";
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
