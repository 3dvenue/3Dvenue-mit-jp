<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
$vid = $_SESSION['vid'] ?? 0;
$cid = $login_id;

if (isset($_POST['image'])) {
    $imgData = $_POST['image'];
    $imgData = preg_replace('/^data:image\/png;base64,/', '', $imgData);
    $imgData = base64_decode(str_replace(' ', '+', $imgData));

    $image = imagecreatefromstring($imgData);
    
    if (!$image) {
        http_response_code(500);
        exit("画像の読み込みに失敗しました。");
    }

    imagealphablending($image, false);
    imagesavealpha($image, true);

    $dir = "../que/$vid";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filePath = "$dir/$cid.webp";

    if (imagewebp($image, $filePath, 85)) {
        echo "アップロード成功: $cid.webp";
    } else {
        http_response_code(500);
        echo "アップロードに失敗しました。";
    }

    imagedestroy($image);
} else {
    http_response_code(400);
    echo "必要なデータがありません。";
}
?>