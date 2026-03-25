<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */
require_once "../config.php";
include_once "auth.php";

if (!empty($_POST['company'])) {
    $company = $_POST['company'];
    $oname = $_POST['oname'];
    $email = $_POST['email'];
    $telno = $_POST['telno'];
    $zip = $_POST['zip'];
    $prefecture = $_POST['prefecture'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];

    $sql = "UPDATE organizer SET company=?, oname=?, email=?, telno=?, zip=?, prefecture=?, address1=?, address2=? WHERE oid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $company, $oname, $email, $telno, $zip, $prefecture, $address1, $address2, $oid);
    $stmt->execute();
    header("Location: acount.php");
    exit;
}

$sql = "SELECT * FROM organizer WHERE oid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if($row){
    $company = $row['company'];
    $oname = $row['oname'];
    $email = $row['email'];
    $password = $row['password'];
    $telno = $row['telno'];
    $zip = $row['zip'];
    $prefecture = $row['prefecture'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/acount.css?t=<?=time()?>">
    <link rel="icon" href="../favicon.ico">
    <title>アカウント情報</title>
</head>
<body class="acount">
<?php include_once 'header.php'; ?>
<main>
<div class="inner">

<section id="acount">
<h2>主催者アカウント</h2>
    <div id="form">
        <form method="post">
            <input type="hidden" name="id" id="id" value="<?=$oid?>" />

            <label>
                <span>団体名</span>
                <input type="text" name="company" value="<?=$company?>" placeholder="団体名" required />
            </label>

            <label>
                <span>担当者名</span>
                <input type="text" name="oname" placeholder="担当者名" value="<?=$oname?>" required />
            </label>

            <label>
                <span>メールアドレス</span>
                <input type="text" name="email" placeholder="メールアドレス" value="<?=$email?>" required />
            </label>

            <label>
                <span>電話番号</span>
                <input type="text" name="telno" placeholder="電話番号" value="<?=$telno?>" required />
            </label>

            <label>
                <span>郵便番号</span>
                <input type="text" name="zip" placeholder="郵便番号" value="<?=$zip?>" required />
            </label>

            <label>
                <span>都道府県</span>
                <input type="text" name="prefecture" placeholder="都道府県" value="<?=$prefecture?>" required />
            </label>

            <label>
                <span>住所1</span>
                <input type="text" name="address1" placeholder="住所1" value="<?=$address1?>" required />
            </label>

            <label>
                <span>住所2</span>
                <input type="text" name="address2" placeholder="住所2" value="<?=$address2?>" />
            </label>

            <div id="button">
                <button type="submit" class="btn">更新</button>
            </div>
        </form>
    </div>
</section>

</div>
</main>
<?php include_once 'footer.php'; ?>
</body>
</html>