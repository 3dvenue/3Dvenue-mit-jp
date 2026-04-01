<?
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

session_start();
unset($_SESSION['login_email']);
unset($_SESSION['login_id']);
unset($_SESSION['login_uuid']);
unset($_SESSION['company']);

header("Location: ../login.php");
exit;
?>