<?php
// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `actuators_parameters`.`id`, `actuators_parameters`.`param`, `actuators_parameters`.`type`, `actuators_parameters`.`comment`, `actuators`.`location`
FROM `actuators_parameters`, `actuators`
WHERE `actuators_parameters`.`id` = `actuators`.`id`
ORDER BY `actuators_parameters`.`id` ASC, `actuators_parameters`.`param` ASC
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
