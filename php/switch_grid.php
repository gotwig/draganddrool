<?php	

include("connect_inc.php");

session_start();
session_name('draganddrool');
	
$_SESSION['gridid'] = $_POST['id'];

$sql = 'UPDATE login SET lastusedgrid='.$_SESSION['gridid'].' WHERE id='.$_SESSION['id'];

mysqli_query($link,$sql);

?>