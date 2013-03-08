<?php
require_once 'connect_inc.php';
require_once '../lib/Session.php';
require_once '../lib/Grid.php';

Session::init();
Grid::removeGrid();