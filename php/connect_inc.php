<?php

  require 'pwd_inc.php';
  
  $link = mysqli_connect($db_server,$db_user,$db_pwd,$db_name);
  
  if (  false == $link )
      die('No connection to MySQL Server!');
/*

new code versus sql injection:
	$db = new PDO('mysqli:host=localhost;dbname=bbm3h11bgo_grid;charset=UTF-8', 'bbm3h11bgo_grid', 'test');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

*/

?>
