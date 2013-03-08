<?php
require_once 'connect_inc.php';

$boxes = $_POST['data'];
foreach ($boxes as $box)
{
	$box = array_map('parseInt', $box);
	$sql = "UPDATE gridentries SET datarow='{$box['row']}' , datacolumn='{$box['col']}' WHERE id='{$box['id']}'";
	mysqli_query($link,$sql);

}

function parseInt($v)
{
	return (int) $v;
}

echo $sql;