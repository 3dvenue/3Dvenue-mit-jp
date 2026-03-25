<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

session_start();
$cheked = $_SESSION['ADMIN_CHECK'] ?? "";

// echo $cheked;

if($cheked !=="success" ){
	$_SESSION = array();
    session_destroy();
	header("Location: login.php"); //Redirect to fake top page
}
?>