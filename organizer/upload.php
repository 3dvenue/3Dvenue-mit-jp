<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";

$id = $_POST['id'] ?? null;
if ($id === null) {
    header("Location: index.php");
    exit;
}

if (empty($_FILES['photo']['tmp_name'])) {
    exit('ファイルが選択されていません。'); 
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);

$allowed = ['image/jpeg', 'image/png', 'image/gif'];

if (!in_array($mime, $allowed, true)) {
    exit('無効なファイル形式です。');
}

// $uploadDir = __DIR__ . '/../expo/'.$id;
$uploadDir = __DIR__ . '/../expo/img/';

// フォルダが無ければ作成（安全な 0755）
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!empty($_FILES['photo']['tmp_name'])) {
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $tmp  = $_FILES['photo']['tmp_name'];
    // $name = basename($_FILES['photo']['name']);
    $dest = $uploadDir . $id .'.' . $ext;

    if (move_uploaded_file($tmp, $dest)) {
        echo "アップロードに成功しました。";
    } else {
        echo "アップロードに失敗しました。";
    }
}

include_once "../config.php";
$sql = "UPDATE venue SET background = '$ext' WHERE id = '$id'";
$stmt = $conn->prepare($sql);
$stmt->execute();

header("Location: editExpo.php?id=$id");
exit;
?>