<?php
include("connect_inc.php");

	session_start();


	session_name('draganddrool');


	

	
$sql1 = "INSERT INTO `login`(`id`, `name`, `pwd`, `email`, `ban`, `lastusedgrid`) VALUES ('','".$_POST['new_username']."','".md5($_POST['new_password'])."','".$_POST['new_email']."', '', NULL)";
	
	
	mysqli_query($link,$sql1);

$sql2 =	"INSERT INTO `grid`(`id`, `name`, `ownerid`, `lastchange`) VALUES ('', 'Put new Gridname here', '".mysqli_insert_id($link)."', CURRENT_TIMESTAMP)";


	$account_id = mysqli_insert_id($link);

	mysqli_query($link,$sql2);

$sql3 = 'UPDATE login SET lastusedgrid="' . mysqli_insert_id($link) .'" WHERE id='.$account_id ;

$_SESSION['gridid'] = mysqli_insert_id($link);

	mysqli_query($link,$sql3);


$_SESSION['id'] = $account_id;
$_SESSION['usr'] = $_POST['new_username'];


header("Location: ../project.php");


?>