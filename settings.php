<?php
require_once 'lib/Session.php';
require_once 'lib/Login.php';

Session::init();
Login::checkLoginRedirect();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<style>
	body {margin:50px !important;}
	#main{margin: 30px;}
	</style>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Drag&amp;Drool Settings</title>
	    <link rel="stylesheet" type="text/css" href="grid.css">
	</head>
	
	<body>
		<h2>
			<a id="logo" href="index.php">Drag&amp;<br>Drool</a>
		</h2>
	    <h2 style="margin-top:0px;margin:20px;">Settings</h2>
	
		<div id="main">
		    <div class="container">
		    </div>
		    
		    <div class="container">
		    </div>
		    
		    <div class="container tutorial-info">
		  		This page is not completed yet. Please check back for more futures.</div>
			</div>
	</body>
</html>