<?php

	include("connect_inc.php");
	

	$content = $_POST['content']; // get posted data
	$content = mysqli_real_escape_string($link,$content);	//escape string	
	
	$type = $_POST['type'];

	$sql = 'UPDATE gridentries SET content="' . $content .'", type="'. $type .'" WHERE id=' . $_POST['id'];
	
	if ($_POST['fontsize'] != "NULL"){
		$sql = 'UPDATE gridentries SET fontsize="'.$_POST['fontsize'].'" WHERE id=' . $_POST['id'];
	}


	mysqli_query($link,$sql);


?>
