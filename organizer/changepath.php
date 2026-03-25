<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
include_once "../config.php";

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? "";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE organizer SET password = ? WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hash, $oid); 
    $stmt->execute();
    $message = "パスワードを変更しました。";
}

?>

</html>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/changepath.css">
    <link rel="icon" href="../favicon.ico">
    <title>パスワード変更</title>
</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h1>パスワード変更</h1>
    <div id="message"><?=$message?></div>

<section id="changepath">
<p class="japan">
    パスワードには漢字やひらがな、全角カナ、記号などが利用可能です。<br />
    あなたが覚えておきやすい組み合わせで入力してください。<br />
    例）古田係長（５４歳）通称：TAKO　なんでタコやねん！<br />
    この組み合わせなら量子コンピュータを使っても解読は極めて困難です。
</p>

<div id="form">
    <form method="POST">
    <h2>新しいパスワード</h2>
    <input type="text" name="password" value="" placeholder="8文字以上（英数・記号・全角文字対応）" />
    <button type="submit" class="btn" name="submit">変更</button>
    </form>
</div>
</section>
</div>
</main>
<?php include_once 'footer.php'; ?>
</body>
</html>