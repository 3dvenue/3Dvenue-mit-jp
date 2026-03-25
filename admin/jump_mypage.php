<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
include_once "auth.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$id = $_GET['id'];
require_once "../config.php";
$sql = "SELECT * FROM company WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
	$_SESSION['login_email'] = $row['email'];
	$_SESSION['login_id'] = $row['cid'];
	$_SESSION['login_uuid'] = $row['uuid'];
	$_SESSION['company'] = $row['company'];
    header("Location: ../mypage/");
    exit;
}
?>