<?php
require_once 'connect_inc.php';
require_once '../lib/Session.php';

Session::init();

$t = mysqli_query($link, "INSERT INTO `grid`(`id`, `name`, `ownerid`, `lastchange`) VALUES ('', 'Put new Gridname here', '".$_SESSION['id']."', CURRENT_TIMESTAMP)");

if ( !$t ) 
{
	die('Error INSERT: ' . mysqli_error($link));
}

echo mysqli_insert_id($link);
