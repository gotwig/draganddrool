<?php
require_once 'connect_inc.php';
require_once '../lib/Session.php';

Session::init();
	
$id = $_POST['id'];
$grid = $_SESSION['gridid'];


$sql1 = "DELETE FROM `gridentries` WHERE `gridtable`=".$id.";";

if($id==$grid){
$sql1_2 = 'UPDATE login SET lastusedid=null WHERE id='.$_POST['id'];
$t = mysqli_query($link,$sql1_2);
}


$sql2 .= "DELETE FROM `grid` WHERE `id`=".$id.";";

$t = mysqli_query($link,$sql1);
   	if ( !$t ) {
   		die('Fehler beim DELETE: ' . mysqli_error()); }

$t = mysqli_query($link,$sql2);
   	if ( !$t ) {
   		die('Fehler beim DELETE: ' . mysqli_error()); }


	echo '0';