<?php
require_once 'connect_inc.php';
include("save_timestamp.php");

$value = $_POST['value']; // get posted data
$value = mysqli_real_escape_string($link,$value);	//escape string

$sql = 'UPDATE grid SET name="' . $value .'", lastchange="' . date("Y-m-d H:i:s") . '" WHERE id='.$_POST['id'] ;

mysqli_query($link,$sql);
print $_POST['value'];
