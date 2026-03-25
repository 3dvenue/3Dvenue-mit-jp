<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
require_once('auth.php');
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/expo.css">
    <link rel="icon" href="../favicon.ico">
    <title>
        <?=$title?>
    </title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h1>展示会一覧</h1>

<section id="expo">
<h2>展示会リスト</h2>

<div id="expobox">
<?php
    $sql = "SELECT * FROM venue JOIN organizer ON venue.organizer = organizer.oid";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $oid = $row['oid'];
        $company = $row['company'];
        $name = $row['name'];
        $description = $row['description'];
        $category = $row['category'];
        $start = $row['start'];
        $end = $row['end'];
        $organizer = $row['organizer'];
        $imagePath = '../expo/img/bana'.$id.'.png';
        if (!file_exists($imagePath)) {
        $imagePath = '../expo/img/nobana.png';
        }
?>
<div class="expo">
<div class="company" data-id="<?=$id?>" data-oid="<?=$oid?>" data-name="<?=$name?>"><?=$company?></div>
<figure data-name="<?=$name?>" data-id="<?=$id?>"><img src="<?=$imagePath?>"></figure>
<div class="schedule">
<p>開始日: <?=date("Y-m-d", strtotime($row['start']))?></p>
<p>終了日: <?=date("Y-m-d", strtotime($row['end']))?></p>
</div>
<div class="exhibitorbtn"><button class="exhibitor btn" data-id="<?=$id?>" data-name="<?=$name?>">参加企業を確認</button></div>
</div>
<?php  } ?>
</div>

<div id="frame">
        <div class="close">&times;</div>
        <iframe src="../expo/1/"></iframe>
</div>

</section>

</div>
</main>
<?php include_once '../footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

        $('#form .close').on('click',function(){
            $('#form').removeClass();
        });

        $('.exhibitor').on('click',function(){
            let id = $(this).data('id');
            let name = $(this).data('name');
            window.location.href = "company.php?vid="+id+"&vname="+name;
        })

        $('.expo .company').on('click',function(){
            var oid = $(this).data('oid');
            var name = $(this).data('name');
            window.location.href = "organizer.php?oid="+oid+"&name="+name;
        })

        $('.expo figure').on('click',function(){
            var id = $(this).data('id');
            $('#frame iframe').attr('src','../expo/'+id);
            $('#frame').addClass('active');
        })

        $('#frame .close').on('click',function(){
            $('#frame').removeClass('active');
        })

    });
</script>
</body>
</html>