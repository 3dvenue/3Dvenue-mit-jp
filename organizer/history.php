<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
$oid = $_SESSION['oid'];
include_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $submit = $_POST['submit'];
  $name = $_POST['name'];
  $eid = $_POST['id'];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/history.css">
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
    <h1>履歴</h1>
<section id="expo">
<h2>アクセス履歴</h2>
<div id="histories">
    <?php
        $sql = "SELECT * FROM venue WHERE organizer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $oid);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = $row['name'];
            $subtitle = $row['subtitle'];
            $description = $row['description'];
            $category = $row['category'];
            $start = $row['start'];
            $end = $row['end'];
            $organizer = $row['organizer'];
            $public = "OK";

            $imagePath = '../que/'.$id.'/bana.webp';
            if (!file_exists($imagePath)) {
                $public = "NG";
                $imagePath = '../que/nobana.webp';
            }

            $logpath = '../que/'.$id.'/access.log';
            if (!file_exists($logpath)) {
                touch($logpath);     // 存在を確定させる
                chmod($logpath, 0666); // 読み書き権限を整える
            }

            $lines = file('../que/'.$id.'/access.log');

    ?>
    <figure data-id="<?=$id?>">
    <img src="<?=$imagePath?>">
    <figcaption>アクセス数: <?=count($lines)?> 回</figcaption>
    </figure>
<?php  } ?>

</div>
</section>

</div>
</main>
<div id="analyze">
    <div id="close">✕</div>
    <iframe id="iframe" src="analyze.php"></iframe>
</div>
<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

        $('#histories figure').on('click',function(){
            let id = $(this).data('id');
            let url = "analyze.php?i="+id;
            $('#iframe').attr('src',url);
            $('#analyze').addClass('active');
        })

        $('#analyze #close').on('click',function(){
            $('#analyze').removeClass('active');
            console.log('click');
        })

    });
</script>
</body>
</html>