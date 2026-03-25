<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $public = $_POST['public'] ?? '0';
    $vid = $_POST['vid'] ?? '0';
    $sql = "UPDATE venue SET public = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $public, $vid); 
    $stmt->execute();
    echo "OK";
    exit;
}
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
    <title>展示会登録</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h1>展示会登録</h1>
<button id="addNew">＋</button>
<section id="expo">
<h2>展示会一覧</h2>
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
        $public = $row['public'];
        $imagePath = '../expo/img/bana'.$id.'.png';
        $ng = "";
        if (!file_exists($imagePath)) {
        $imagePath = '../expo/img/nobana.png';
        $ng = "NG";
        }
        $checked = "";
        if($public == "1"){
        $checked = "checked";
        }
?>

<div class="expo">
<a href="editExpo.php?id=<?=$id?>" class="expoEdit">編集</a>
<h3><?=$name?></h3>
    <div class="expomemo">
        <figure class="left">
            <img src="<?=$imagePath?>">
        </figure>
        <div class="right">
            <h4>- <?=$subtitle?> -</h4>
            <div class="duration">
                <p>開催期間：</p>
                <span><?=date("Y-m-d", strtotime($row['start']))?></span>
                <span>～</span>
                <span><?=date("Y-m-d", strtotime($row['end']))?></span>
            </div>
            <div class="public">
                <label class="<?=$ng?>">公開： <input type="checkbox" class="public" data-id="<?=$id?>" name="public" value="1" <?=$checked?>><span class="CheckBox"></span></label>
                <div class="exhibitors" data-id="<?=$id?>">出展者</div>
            </div>
        </div>
 
    </div>
</div>

<?php  } ?>
</section>

</div>
</main>
<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>

<script type="text/javascript">
    $(function(){

        $('#form .close').on('click',function(){
            $('#form').removeClass();
        });

        $('#addNew').on('click',function(){
            window.location.href = 'addnew.php';
        })

        $('.exhibitors').on('click',function(){
            let vid = $(this).data('id');
            window.location.href = 'exhibitors.php?vid='+vid;
        })

        $('input.public').on('change',function(){
            let public = 0;
            let id = $(this).data('id');
            let isChecked = $(this).prop('checked');
            if(isChecked == true){
                public = 1;
            }
            $.post("expo.php",{ public:public,vid:id},function(data){
                console.log(data);
              }
            );
        })


    });
</script>
</body>
</html>