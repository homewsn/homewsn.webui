<?php
// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `parameters`.`type`
FROM `parameters`, `sensors`
WHERE (`parameters`.`type`!= '' AND `parameters`.`id` = `sensors`.`id`)
GROUP BY `parameters`.`type` ASC
";

$result = mysqli_query($link, $sql) or die('Error ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
	extract($row);
	$rows[] = "\"$type\"";
}

// output to the browser
header('Content-Type: text/javascript; charset=UTF-8');
echo "[\n" .join(",\n", $rows) ."\n]";
?>
