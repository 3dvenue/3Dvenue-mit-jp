<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
$oid = $_SESSION['oid'];
require_once "../config.php";

$name = "新規展示会タイトル";
$subtitle = "サブタイトルまたは英語タイトル";
$description = "展示会の説明を500文字以内で入力してください。";
$start = date('Y-m-d', strtotime('+30 days'));
$end = date('Y-m-d', strtotime('+37 days'));

$sql = "INSERT INTO venue (name,subtitle,description,start,end,organizer) VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssi',$name ,$subtitle ,$description ,$start ,$end ,$oid);

if ($stmt->execute()) {
    $newId = $conn->insert_id;

    $dirPath = '../expo/' . $newId;
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
        chmod($dirPath, 0777);
    }

    $logFile = $dirPath . '/access.log';
    if (!file_exists($logFile)) {
        touch($logFile);
        chmod($logFile, 0666);
    }
}

$stmt->close();
header("Location: expo.php");
exit;
?>