<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/
include_once "auth.php";
$name = $_POST['name'] ?? null;
$color = $_POST['color'] ?? null;
$id = $_POST['id'] ?? null;

// ID と color の存在チェック
if ($id === null || $name === null || $color === null) {
    http_response_code(400);
    exit('NG');
}

if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
    http_response_code(400);
    exit('NG');
}

// DB 接続
include_once "../config.php"; // 例：PDO を返すファイル

$sql = "UPDATE venue SET $name = '$color' WHERE id = '$id'";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    echo 'OK';
} else {
    echo 'NG';
}

?>