<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
require_once('auth.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$o =  $_GET['o'];
	$c = $_GET['c'];
	$_SESSION['oid'] = $o;
	$_SESSION['comapny_name'] = $c;

	header("Location: ../organizer/index.php");
	exit;
}
?>