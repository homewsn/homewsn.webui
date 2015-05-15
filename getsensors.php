<?php
// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `sensors`.`id`, `sensors`.`ip`, `sensors`.`location`
FROM `sensors`
ORDER BY `sensors`.`id` ASC
";

$result = mysqli_query($link, $sql) or die('Error ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
	extract($row);
	$rows[] = "{ \"id\": \"$id\", \"ip\": \"$ip\", \"location\": \"$location\"}";
}

// output to the browser
header('Content-Type: text/javascript; charset=UTF-8');
echo "[\n" .join(",\n", $rows) ."\n]";
?>
