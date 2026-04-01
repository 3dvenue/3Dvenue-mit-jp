<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

session_start();
unset($_SESSION['oid']);
unset($_SESSION['comapny_name']);
header("Location: ./");
exit;
?>