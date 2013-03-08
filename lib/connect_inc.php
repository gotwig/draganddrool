<?php
require_once 'conf/Config.php';

$link = mysqli_connect(Config::MYSQL_SERVER, Config::MYSQL_USER, Config::MYSQL_PASS, Config::DEFAULT_DATABASE);