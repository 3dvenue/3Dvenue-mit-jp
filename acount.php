<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();

$result = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "./config.php";
    $submit = $_POST['submit'] ?? "";
    $email = $_POST['email'] ?? "";
    $company = $_POST['company'] ?? "";
    $name = $_POST['name'] ?? "";
    $password = $_POST['password'] ?? "";
    $telno = $_POST['telno'] ?? "";
    $zip = $_POST['zip'] ?? "";
    $prefecture = $_POST['prefecture'] ?? "";
    $address1 = $_POST['address1'] ?? "";
    $address2 = $_POST['address2'] ?? "";

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $result = "パスワードは8文字以上で、大文字・小文字・数字を含めてください。";
        } elseif (strpos(strtolower($password), 'password') !== false) {
            $result = "このパスワードは単純すぎます。";
        } else {

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT IGNORE INTO company (email, company, name, password, telno, zip, prefecture, address1, address2) VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss",$email, $company, $name, $hashed_password, $telno, $zip, $prefecture, $address1, $address2);
    $stmt->execute();
        if ($stmt->affected_rows > 0) {
                $result = "登録が完了しました。";
        } else {
                $result = "このメールアドレスは既に登録されています。";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./common/css/base.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="./common/css/style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="./common/css/login.css?t=<?=time()?>">
    <link rel="icon" href="../favicon.ico">
    <title>出展者事前登録</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<h1 id="head">出展者事前登録</h1>
<main>
<div class="inner">
    <section id="login">
        <h2>事前申請フォーム</h2>
        <ol>
            <li>3DVenueは様々なサーバー環境で安定して動作させるため、自動メール通知は行っておりません。</li>
            <li>申請：メールアドレスとパスワードを入力し、情報を送信してください。</li>
            <li>審査：管理者が内容を確認します。</li>
            <li>有効化：承認後にアカウントが有効になります。承認完了まではログインできません。</li>
            <li>注意：コミュニティ保護のため、管理者の判断により申請を却下または削除する場合があります。</li>
        </ol>

        <div id="form">
            <form method="post">
                <div>
                    <div id="result"><?=$result?></div>
                    <label for="company">会社名</label>
                    <input type="text" name="company" id="company" value="" placeholder="会社名" required />
                    <label for="name">担当者名</label>
                    <input type="text" name="name" id="name" value="" placeholder="担当者名" required />
                    <label for="email">メールアドレス</label>
                    <input type="text" name="email" id="email" value="" placeholder="account@example.com" required />
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password" value="" placeholder="8文字以上（英大文字・小文字・数字含む）" required />
                    <label for="telno">電話番号</label>
                    <input type="telno" name="telno" id="telno" value="" placeholder="電話番号" required />
                    <label for="zip">郵便番号</label>
                    <input type="text" name="zip" id="zip" value="" placeholder="郵便番号" required />
                    <label for="prefecture">都道府県</label>
                    <input type="text" name="prefecture" id="prefecture" value="" placeholder="" required />
                    <label for="address1">住所1</label>
                    <input type="text" name="address1" id="address1" value="" placeholder="" required />
                    <label for="address2">住所2</label>
                    <input type="text" name="address2" id="address2" value="" placeholder="" />
                    <div id="applicationbtn"><button type="submit" name="submit" class="btn" value="register">申請する</button></div> 
                </div>
            </form>
        </div>
    </section>
</div>
</main>
<?php include_once('footer.php')?>
</body>
</html>