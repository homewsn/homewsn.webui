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

// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `parameters`.`id`, `parameters`.`param`, `parameters`.`type`, `parameters`.`comment`, `devices`.`location`
FROM `parameters`, `devices`
WHERE `parameters`.`id` = `devices`.`id` AND `parameters`.`param_type`='actuator'
ORDER BY `parameters`.`id` ASC, `parameters`.`param` ASC
";

$result = mysqli_query($link, $sql) or die('Error ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
	extract($row);
	if ($comment)
		$rows[] = '{"topic": "actuators/' ."$id/$param" .'", "desc": "' ."$location $type $comment" .'"}';
	else
		$rows[] = '{"topic": "actuators/' ."$id/$param" .'", "desc": "' ."$location $type" .'"}';
}

// output to the browser
header('Content-Type: text/javascript; charset=UTF-8');
echo "[\n" .join(",\n", $rows) ."\n]";
?>
