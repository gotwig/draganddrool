<?php
require_once 'connect_inc.php';

require_once '../lib/Session.php';

Session::init();

$id = $_POST['id'];
$actualgrid = $_SESSION['gridid'];

$t = mysqli_query($link, "DELETE FROM gridentries WHERE id=".$id);

if(!$t)
{
	die('Error DELETE: ' . mysqli_error());
}