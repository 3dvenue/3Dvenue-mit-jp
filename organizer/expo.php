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

if (isset($_GET['color1'])) {
    $c1 = $_GET['color1'];
    $c2 = $_GET['color2'];
    $id = $_GET['id'];

    $dir = "../que/{$id}/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);
    file_put_contents($dir . "color", $c1 . "," . $c2);    
    echo "ok";
    exit;
}


if ($_POST['submit'] === 'trash') {
    $id = $_POST['id'];
    $file = "../que/$id/top.webp";

    if (file_exists($file)) {
        unlink($file);
        echo "deleted";
    } else {
        echo "notfound";
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image'])) {
    $id = $_POST['id'];
    $data = $_POST['image'];
    $base64 = preg_replace('/^data:image\/png;base64,/', '', $data);
    $binary = base64_decode(str_replace(' ', '+', $base64));
    $img = imagecreatefromstring($binary);
    if ($img !== false) {
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $dir = "../que/" . $id;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
       imagewebp($img, $dir . "/bg.webp", 80); 
        imagedestroy($img);

        echo "saved";
    } else {
        echo "error";
    }
    exit;
}

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
        $imagePath = '../que/'.$id.'/bana.webp';
        $ng = "";
        if (!is_file($imagePath)) {
            $imagePath = '../que/nobana.webp';
            $ng = "NG";
        }
        $checked = "";
        if($public == "1"){
        $checked = "checked";
        }

        $bgimg = "";
        $path = '../que/'.$id.'/bg.webp';
        if (is_file($path)) {
            $bgimg = 'background-image:url(../que/'.$id.'/bg.webp?t='.time().')';
        }

        $color = '#7BB4EE,#0E3B5C';
        $path = '../que/'.$id.'/color';
        if (is_file($path)) {
            $color = file_get_contents($path);
        }

?>

<div class="expo">
<a href="editExpo.php?id=<?=$id?>" class="expoEdit">編集</a>
<h3><?=$name?></h3>
    <div class="expomemo">
        <figure id="f<?=$id?>" class="left" data-id="<?=$id?>" data-image="<?=$bgimg?>" data-color="<?=$color?>">
            <img src="<?=$imagePath?>?t=<?=time()?>">
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
<div id="expoframe">
<div class="after"></div>
<div id="categories">
    <div class="inner">
        <ul><li>カテゴリー</li><li>カテゴリー</li><li>カテゴリー</li></ul></div>
    </div>
    <div id="floor">
        <div id="bgcolor">
            <span>背景：</span>
            <input type="hidden" name="id" id="id" value="">
            <input type="color" name="color1" id="color1" value="#7BB4EE">
            <input type="color" name="color2" id="color2" value="#0E3B5C">
        </div>
        <div id="bgfile">
            <label for="background"><img src="../img/cloud.svg"></label>
            <input type="file" name="background" id="background">
        </div>
        <div>16:9を推薦</div>
        <div id="trash">
            <span class="trash"><img src="../img/trash.svg"></span>
        </div>
        <div id="savebtn"><span class="btn">背景画像を保存する</span></div>
    </div>
    <div class="close">✕</div>
</div>

<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script src="../common/js/dom-to-image.min.js"></script>
<script type="text/javascript">
    $(function(){

        $('#form .close').on('click',function(){
            $('#form').removeClass();
        });

        //新規エキスポデータを作成
        $('#addNew').on('click',function(){
            window.location.href = 'addnew.php';
        })

        //エキスポの参加者データに切替
        $('.exhibitors').on('click',function(){
            let vid = $(this).data('id');
            window.location.href = 'exhibitors.php?vid='+vid;
        })

        //エキスポデータでエディター画面を表示
        $('.left').on('click',function(){
            let id = $(this).data('id');
            let image = $(this).attr('data-image');
            let colors = $(this).attr('data-color').split(',');
            let c1 = colors[0];
            let c2 = colors[1];
            $('#color1').val(c1);
            $('#color2').val(c2);
            $('#id').val(id);
            $('#expoframe').addClass('active');
            $('#expoframe .after').attr('style',image);
            $('#expoframe').css('background','linear-gradient('+c1+','+c2+')');
            if(image !== ''){
                $('#trash').addClass('active');
            }else{
                $('#trash').removeClass();                
            }
        })

        //カラーデータのリアルタイム反映
        $('#color1,#color2').on('input',function(){
            let c1 =  $('#color1').val();   
            let c2 =  $('#color2').val();            
            $('#expoframe').css('background','linear-gradient('+c1+','+c2+')');
        })

        //カラーデータの取得
        $('#color1,#color2').on('change',function(){
            let id =  $('#id').val();   
            let c1 =  $('#color1').val();   
            let c2 =  $('#color2').val();
            $.get('expo.php', {
                id:id,
                color1: c1,
                color2: c2
            }, function(res){
                console.log(res); // "ok" が返ってくる
            });
            $('#f'+id+'.left').attr('data-color',c1+','+c2);
        })

        //アップロード画像を取得
        $('#background').on('change', function() {
            const file = this.files[0];
            if (!file) return;
            const blobUrl = URL.createObjectURL(file);
            $('#expoframe .after').css('background-image', 'url(' + blobUrl + ')');
            $('#savebtn').addClass('active');
        });

        //背景画像の保存
        $('#savebtn .btn').on('click', function () {
            const node = document.querySelector('#expoframe .after');
            $('#expoframe .after').css('margin','0');
            let id = $('#id').val();
            domtoimage.toPng(node)
            .then(function (dataUrl) {
                $.post(location.href, {
                    id: id,
                    image: dataUrl
                }, function(res){
                    console.log(res);
                    $('#expoframe .after').css('margin','0 auto');
                    $('#f'+id).attr('data-image', 'background-image:url(../que/'+id+'/bg.webp?'+Date.now()+')');
                    $('#background').val('');
                    $('#trash').addClass('active');
                    $('#savebtn').removeClass('active');
               });
            });
        });


        //エディター画面の終了
        $('.close').on('click',function(){
            $('#expoframe').removeClass('active');
            $('#savebtn').removeClass('active');
            $('#background').val('');
        })

        //背景画像を削除
        $('#trash').on('click',function(){
            let id = $('#id').val();
            $.post(location.href, {
                id: id,
                submit:'trash'
            }, function(res){
               $('#f'+id).attr('data-image', '');
               $('#expoframe .after').css('background-image','');
               $('#savebtn').removeClass('active');
               $('#background').val('');
               $('#trash').removeClass();
            })
        })

        $('input.public').on('change',function(){
            let public = 0;
            let id = $(this).data('id');
            let isChecked = $(this).prop('checked');
            if(isChecked == true){
                public = 1;
            }
            $.post("expo.php",{ public:public,vid:id},
                function(data){
                console.log(data);
                }
            );
        })


    });
</script>
</body>
</html>