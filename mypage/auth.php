<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

session_start();

if (!isset($_SESSION['login_id'], $_SESSION['login_uuid'], $_SESSION['login_email'])) {
    header("Location: /login.php");
    exit;
}
$login_id = $_SESSION['login_id'];
$login_uuid = $_SESSION['login_uuid'];
$login_email = $_SESSION['login_email'];
$company = $_SESSION['company'];
?>