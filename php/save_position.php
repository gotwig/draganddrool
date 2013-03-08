<?php
require_once 'connect_inc.php';

$boxes = $_POST['data'];
foreach ($boxes as $box)
{
	$box = array_map('intval', $box);
	$sql = "UPDATE gridentries SET datarow='{$box['row']}' , datacolumn='{$box['col']}' WHERE id='{$box['id']}'";
	mysqli_query($link,$sql);

}

echo $sql;