<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

session_start();
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $email = trim($_POST['email'] ?? '');
   $pass = $_POST['password'] ?? '';

    include_once "../config.php";

    $stmt = $conn->prepare('SELECT * FROM organizer WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['oid'] = $row['oid'];
        $_SESSION['comapny_name'] = $row['company'];
        $message = 'ログインに成功しました。';
        header('Location: index.php');
    } else {
        $message = 'ユーザー名またはパスワードが正しくありません。';
    }
}
?>

</html>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/login.css">
    <link rel="icon" href="../favicon.ico">
    <title>主催者</title>
<style type="text/css">
</style>

</head>
<body>
<header>
    <div class="inner">
        <div id="logo">
            <img src="../img/logo.png" alt="3DVenue" />
        </div>
    </div>
</header>
<main>
    <div class="inner">
    <h1>ログイン</h1>

<?=$message?>

<section>
<h2>主催者</h2>
<div id="form">
    <div id="login">
        <form method="post">
        <label><span>アカウント：</span><input type="text" name="email" id="email" placeholder="メールアドレス" /></label>
        <label><span>パスワード：</span><input type="password" name="password" id="password" placeholder="パスワード" /><span class="eyeball"></span></label>
            <div id="button">
                <button type="submit" class="btn" name="submit" id="login" value="login">ログイン</button>
            </div>
        </form>
    </div>
</div>
</section>
</div>
</main>
<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

$('label .eyeball').on('click', function() {
    const $input = $(this).prev('input');
    if ($input.attr('type') === 'password') {
        $input.attr('type', 'text');
        $(this).addClass('close');
    } else {
        $input.attr('type', 'password');
        $(this).removeClass('close');
    }
});
    });
</script>
</body>
</html>