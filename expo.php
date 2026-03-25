<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
require_once "./config.php";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./common/css/style.css">
    <link rel="stylesheet" type="text/css" href="./common/css/expo.css">
    <link rel="icon" href="./favicon.ico">
    <title>現在開催中の展示会</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<h1 id="head">現在開催中の展示会</h1>
<main>
<div class="inner">
<section id="venue">
<h2>現在開催中の展示会</h2>
<ul id="venues">
<?php
    $sql = "SELECT * FROM venue WHERE CURRENT_DATE BETWEEN start AND end AND public = 1 ORDER BY id DESC";
    $vanues = [];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $values = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($values as $row) {
        $id = $row['id'];
        $name = $row['name'];
?>
<li data-id="<?=$id?>">
    <figure>
        <img src="./expo/img/bana<?=$id?>.png" alt="<?=$name?>">
    </figure>
</li>
<?php } ?>
</section>
</div>
</main>

<div id="view">
    <section>
        <div id="close">&times;</div>
        <iframe id="expo"></iframe>
    </section>
</div>

<?php include_once('footer.php')?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
$(function(){

    $('#venue li').on('click', function() {
        const id = $(this).data('id');
        $('#view').addClass('active');
        $('#view section iframe').attr('src','./expo/'+id);


    });

    $('#close').on('click',function(){
        $('#view').removeClass();
    })

});
</script>