<?php
// connect to the database
include('mysql.inc');
$link = mysqli_connect($host, $user, $pass, $db) or die('Error ' .mysqli_error($link));

$sql = "
SELECT `parameters`.`id`, `parameters`.`param`, `parameters`.`type`, `parameters`.`comment`, `actuators`.`location`
FROM `parameters`, `actuators`
WHERE `parameters`.`id` = `actuators`.`id`
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
