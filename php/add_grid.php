<?php
include("connect_inc.php");

	session_start();
	
	session_name('draganddrool');

	
$sql = "INSERT INTO `grid`(`id`, `name`, `ownerid`, `lastchange`) VALUES ('', 'Put new Gridname here', '".$_SESSION['id']."', CURRENT_TIMESTAMP)";
	
	$t = mysqli_query($link,$sql);

    	if ( !$t ) {
       		die('Fehler beim INSERT: ' . mysqli_error($link)); }

	echo (mysqli_insert_id($link));

?>
