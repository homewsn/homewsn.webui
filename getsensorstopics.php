<?php
// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `sensors_parameters`.`id`, `sensors_parameters`.`param`, `sensors_parameters`.`type`, `sensors_parameters`.`comment`, `sensors`.`location`
FROM `sensors_parameters`, `sensors`
WHERE `sensors_parameters`.`id` = `sensors`.`id`
ORDER BY `sensors_parameters`.`id` ASC, `sensors_parameters`.`param` ASC
";

$result = mysqli_query($link, $sql) or die('Error ' .mysqli_error($link));
$rows = array();
while ($row = mysqli_fetch_assoc($result))
{
	extract($row);
	if ($comment)
		$rows[] = '{"topic": "sensors/' ."$id/$param" .'", "desc": "' ."$location $type $comment" .'"}';
	else
		$rows[] = '{"topic": "sensors/' ."$id/$param" .'", "desc": "' ."$location $type" .'"}';
}

// output to the browser
header('Content-Type: text/javascript; charset=UTF-8');
echo "[\n" .join(",\n", $rows) ."\n]";
?>
