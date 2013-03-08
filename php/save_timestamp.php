<?php
require_once 'connect_inc.php';

$sql_time = 'UPDATE grid SET lastchange="' . date("Y-m-d H:i:s") . '" WHERE id'.$_POST['id'] ;

mysqli_query($link,$sql_time);