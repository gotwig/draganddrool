<?php
	include("connect_inc.php");
	
	session_start();
	
	session_name('draganddrool');
	
	$id = $_POST['id'];
	$actualgrid = $_SESSION['gridid'];
	$sql = "DELETE FROM gridentries WHERE id=".$id;
	$t = mysqli_query($link,$sql);

    	if ( !$t ) {
       		die('Fehler beim DELETE: ' . mysqli_error()); }



?>