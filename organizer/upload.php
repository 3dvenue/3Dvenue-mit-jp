<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";

// 1. IDの取得とバリデーション
$id = $_POST['id'] ?? null;
if ($id === null) {
    header("Location: index.php");
    exit;
}

// 2. ファイル選択の確認
if (empty($_FILES['photo']['tmp_name'])) {
    exit('ファイルが選択されていません。'); 
}

// 3. MIMEタイプのチェック
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['photo']['tmp_name']);
finfo_close($finfo);

$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($mime, $allowed, true)) {
    exit('無効なファイル形式です。');
}

// 4. 保存ディレクトリの準備（絶対パスを調整）
$uploadDir = __DIR__ . '/../que/' . $id . '/';
// フォルダが無ければ作成（安全な 0755）
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// 5. 画像リソースの作成（JPG/PNG/GIF/WebPを自動認識）
$tmpName = $_FILES['photo']['tmp_name'];
$source = imagecreatefromstring(file_get_contents($tmpName));

if ($source !== false) {
    // 保存名は「top.webp」で固定
    $destWebp = $uploadDir . 'top.webp';

    // --- 透過と色を綺麗に保つ「お作法」3行 ---
    imagepalettetotruecolor($source);
    imagealphablending($source, true);
    imagesavealpha($source, true);
    // ---------------------------------------

    // 6. WebPとして保存（クオリティ80：軽量と高画質のバランス）
    if (imagewebp($source, $destWebp, 80)) {
        // 7. DB更新：拡張子は常に 'webp' として記録
        include_once "../config.php";
        $ext = 'webp'; 
        $sql = "UPDATE venue SET background = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $ext, $id); // プリペアドステートメントで安全に
        $stmt->execute();

        imagedestroy($source);
        header("Location: editExpo.php?id=$id");
        exit;
    } else {
        imagedestroy($source);
        exit("WebP変換に失敗しました。GDライブラリの設定を確認してください。");
    }
} else {
    exit("画像リソースの生成に失敗しました。");
}
?>