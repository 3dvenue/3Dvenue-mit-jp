<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();

// 最初にアカウント名とパスワードを設定してください。
// 漢字、ひらがな、カタカナなどのマルチバイト文字や特殊記号も自由に使用できます。
// 極限までシンプルに設計されています。独自のセキュリティロジックを自由に追加してください。
// わずか数行加えるだけで、このログイン画面を強固な要塞に変えることができます。
// 注意：ハッカーを翻弄するための型破りなセキュリティアイデアについては、READMEを確認してください。

/*
    [セキュリティ・メモ]
    ハッカーを混乱させる手法として、$ipcheck の結果に基づいて 'name' 属性を
    動的に変更（例：name="acount<?=$input1?>"）することが可能です。
    これにより、自動化されたボットが追跡できない「移動標的（ムービング・ターゲット）」を作り出せます。
    その際は、受け取り側の $_POST['acount'.$input1] ロジックも同期させるのを忘れずに！
    -- Concept by Yoshihiro Murai
*/

$acount = "管理者用アカウント";
$password = "管理者用パスワード";

$title="管理者ログイン";
$ipcheck = "127.0.0.1";
$ip = $_SERVER['REMOTE_ADDR'];
$input1 = "email";
$input2 = "password";
$required = "required";
$message = "";

if($ipcheck == $ip){
    $input1 = "text";
    $input2 = "text";
    $required = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // // --- Sender Check (CSRF Prevention) ---
    // $referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : '';
    // $serverName = $_SERVER['HTTP_HOST'];

    // if ($referer !== $serverName) {
    //      header("Location: login.php"); //Jump to fake page.
    //      exit;
    // }

    if (isset($_POST['password'])) {
        $message = "メールアドレスまたはパスワードが正しくありません。";
        // echo "Error";
    }
    if (isset($_POST['acounttext']) && isset($_POST['text'])) {
        if($_POST['acounttext'] === $acount && $_POST['text'] === $password){
        require_once "../config.php";
        // echo "Success";
            $_SESSION['ADMIN_CHECK'] = "success";
            header("Location: index.php");
            exit;
        }
    }

    if (isset($_POST['acount']) && isset($_POST['password'])) {
        if($_POST['acount'] === $acount && $_POST['password'] === $password){
        require_once "../config.php";
            $_SESSION['ADMIN_CHECK'] = "success";
            header("Location: index.php");
            exit;
        }
    }

}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="icon" href="../favicon.ico">
    <title>
        <?=$title?>
    </title>
<style type="text/css">
.inner{
    max-width:680px;
}

h2{
    text-align: center;
}

.error{
    text-align: center;
    margin-bottom:40px;
}

#form{
    max-width: 420px;
    border:1px solid #999;
    background:linear-gradient(#FFF,#EEE);
    padding:20px 40px;
    border-radius: 10px;
    margin: 0 auto;
}

form label{
    display: flex;
    justify-content: space-between;
    padding:0 0 20px;
}

form label input{
    padding:5px 10px;
    border:1px solid #999;
    border-radius: 5px;
}

#submitButton{
    text-align: right;
}

#message{
    text-align: center;
    font-weight:700;
    font-size:12px;
    color:red;
}

</style>
</head>
<body>
<main>
<section id="login">
    <div class="inner">
    <h2><?=$title?></h2>

    <p class="error">あなたのIPアドレスは <strong><?=$ip?></strong> です。<br />
    このシステムはこのアドレスからのログインを許可していません。</p>

    <div id="form">
        <form method="POST">
            <label><span>メールアドレス：</span><input type="<?=$input1?>" name="acount" value="<?=$input1?>" placeholder="acount@example.com" <?=$required?>></label>
            <label><span>パスワード：</span><input type="<?=$input2?>" name="password" value="<?=$input2?>" placeholder="password" <?=$required?>></label>
            <div id="message"><?=$message?></div>
            <div id="submitButton"><button type="submit" class="btn" name="submit" value="login">ログイン</button>
        </form>
    </div>
</section>
</div>
</main>
<?php include_once '../footer.php'; ?>
</body>
</html>