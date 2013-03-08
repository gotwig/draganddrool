<?php
require_once 'connect_inc.php';
require_once '../lib/Grid.php';
require_once '../lib/Session.php';

Session::init();
Grid::saveInfo();