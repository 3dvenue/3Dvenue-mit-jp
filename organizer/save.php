<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
if (!empty($_POST['image']) && isset($_POST['id'])) {

    $id = intval($_POST['id']);

    // ヘッダーを汎用的に置換（JPG/PNG両対応）
    $img = preg_replace('#^data:image/[^;]+;base64,#', '', $_POST['image']);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    // データから画像リソースを自動生成
    $source = imagecreatefromstring($data);

    if ($source !== false) {
        $dir = '../que/'.$id.'/';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // 拡張子を .webp に統一
        $filename = $dir . 'bana.webp';

        imagepalettetotruecolor($source);
        imagealphablending($source, true);
        imagesavealpha($source, true);

        imagewebp($source, $filename, 80);
        imagedestroy($source);

        echo 'saved: bana.webp';
    } else {
        echo 'Error: Invalid image data';
    }
}
?>