<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
$vid = $_SESSION['expoid'];

$cid = isset($_POST['exid']) ? trim($_POST['exid']) : null;

if ($cid === null || $cid === '') {
    http_response_code(400); // Bad Request
    exit('exid is required');
}

include_once "../config.php";


$sql = "UPDATE exhibitors SET click = click + 1 WHERE vid = ? AND cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vid,$cid);
$stmt->execute();


http_response_code(200);
echo 'OK';
?>
