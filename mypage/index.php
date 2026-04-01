<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

require_once('auth.php');
require_once "../config.php";

$sql = "SELECT * FROM company WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $login_id);
$stmt->execute();
$result = $stmt->get_result();
$company_array = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="icon" href="../favicon.ico">
    <title>出展者マイページ</title>
</head>
<body class="top">
<?php include_once 'header.php'; ?>
<main>
<?
if(empty($company)){
    header("Location: acount.php");
    exit;
}
?>
<div class="inner">
<h1>マイページTOP</h1>

<section id="information">
<h2>お知らせ</h2>

<div id="info">
<?php
    $sql = "SELECT * FROM infomation WHERE target = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
    $published_at = date('Y年n月j日',strtotime($row['published_at']));
    $title = $row['title'];
    $content = $row['content'];
?>
<details>
    <summary><date><?=$published_at?>：</date><?=$title?></summary>
    <div><?=$content?></div>
</details>
<?php } ?>
</div>
</section>

<section id="venues">

    <h2>開催中の展示会</h2>

    <div id="venueBox">
      <?php
        $sql = "SELECT v.*, e.id AS exid FROM venue v LEFT JOIN exhibitors e ON v.id = e.vid AND e.cid = ? WHERE v.public = '1'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['name'];
        $start = (new DateTime($row['start']))->format("Y年n月j日");
        $end = (new DateTime($row['end']))->format("Y年n月j日");

        $set = !empty($row['exid']) ? "set" : "";
        $matched_id = $row['exid'] ?? "";
        $bana = "../que/{$id}/bana.webp?t=".time();
      ?>
        <div class="venue <?=$set?>">
            <div class="flex"><figure style="background-image:url(<?=$bana?>)" data-id="<?=$id?>"></figure></div>
            <form action="exhibit.php" method="post">
                <input type="hidden" name="vid" value="<?=$id?>">
                <input type="hidden" name="exid" value="<?=$matched_id?>">
                <button class="btn new" type="submit" name="submit" value="venue">出展申込</button>
                <button class="btn set" type="submit" name="submit" value="edit">編集</button>
            </form>
        </div>
     <?php } ?>
    </div>

</section>

</div>
</main>
<div id="view">
    <div id="close">✕</div>
    <iframe src=""></iframe>
</div>

<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script>
    $(function(){

        $('.venue figure').on('click',function(){
            let id = $(this).data('id');
            $('#view').addClass('active');
            let url = '../expo/'+id+'/';
            $('#view iframe').attr('src',url);
        })

        $('#view #close').on('click',function(){
            $('#view').removeClass('active');
            $('#view iframe').attr('src','');
        })

    })
</script>
</body>
</html>