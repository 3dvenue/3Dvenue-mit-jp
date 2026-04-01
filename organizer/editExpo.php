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
// 曜日を日本語に変更
$week = ['日', '月', '火', '水', '木', '金', '土'];
$categories = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];

    switch ($name) {

        case 'name':
        $text = mysqli_real_escape_string($conn, $_POST['text'] ?? '');
            break; 

        case 'subtitle': 
        $text = mysqli_real_escape_string($conn, $_POST['text'] ?? '');
            break; 

        case 'period': 
            $start = mysqli_real_escape_string($conn, $_POST['start'] ?? '');
            $end   = mysqli_real_escape_string($conn, $_POST['end'] ?? '');
            break; 

        case 'organizers': 
        $text = mysqli_real_escape_string($conn, $_POST['text'] ?? '');
            break; 

        case 'description': 
        $text = mysqli_real_escape_string($conn, $_POST['textarea'] ?? '');
            break; 

        case 'benefit': 
        $text = mysqli_real_escape_string($conn, $_POST['textarea'] ?? '');
            break;
    }

    if($name == 'period'){
        $sql = "UPDATE venue SET start = '$start', end = '$end' WHERE id = $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        header("Location: editExpo.php?id=$id");
        exit;
    }else{
        if($name != "category"){
            $sql = "UPDATE venue SET $name = '$text' WHERE id = '$id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            header("Location: editExpo.php?id=$id");
            exit;
        }
    }

    $submit = $_POST['submit'];

    if($name == 'category'){
        $submit = $_POST['submit'];
        $cdata = $_POST['cdata'] ?? '';
        $category_id = $_POST['category_id'] ?? '0';

        if($submit == "add"){
            $sql = "INSERT INTO category (name,vid) VALUE (?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $cdata, $id);
            $stmt->execute();
            header("Location: editExpo.php?id=$id");
            exit;
        }

        if($submit == "edit"){
            $sql = "UPDATE category SET name = ? WHERE category_id = ? AND vid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $cdata, $category_id, $id);
            $stmt->execute();
            header("Location: editExpo.php?id=$id");
            exit;
        }

        if($submit == "dell"){
            $sql = "DELETE FROM category WHERE category_id = ? AND vid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $category_id, $id);
            $stmt->execute();
            header("Location: editExpo.php?id=$id");
            exit;
        }

    }

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['id']) || $_GET['id'] === '') {
        header("Location: index.php");
        exit;
    }

    $id = (int)($_GET['id'] ?? 0);
    $sql = "SELECT * FROM venue WHERE id = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: index.php");
        exit;
    }

        $name = $row['name'];
        $subtitle = $row['subtitle'];
        $description = $row['description'];
        $benefit = $row['benefit'];
        $category = $row['category'];
        $start = $row['start'];
        $end = $row['end'];
        $benefit = str_replace(["\r\n", "\r"], "\n", $benefit);
        $benefit_list = explode("\n", $benefit);
        $categories = explode(',', $category);

        $h1len = 90 / mb_strlen($name);
        $h2len = 80 / mb_strlen($subtitle);
 
        $background = $row['background'];
        $maincolor = $row['maincolor'];
        $ptext = $row['ptext'];
        $h3text = $row['h3text'];
        $venuecolor = $row['venuecolor'];
        $headercolor = $row['headercolor'];
        $headertext = $row['headertext'];
        $h2color = $row['h2color'];
        $h2text = $row['h2text'];
        $organizers = $row['organizers'];

        $toppath = '../que/'.$id.'/top.webp';
        $bgstyle = "";
        if (file_exists($toppath)) {
            $bgstyle = 'style="background:url(../que/'.$id.'/top.webp?t='.time().')"';
        }

    }else{
        header("Location: index.php");
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
    <link rel="stylesheet" type="text/css" href="./css/addExpo.css">
    <link rel="stylesheet" type="text/css" href="./css/editer.css">
    <link rel="icon" href="../favicon.ico">
    <title>展示会編集 | 3DVenue</title>
<style type="text/css">
header{
    background-color:<?=$headercolor?>;
    color:<?=$headertext?>;
}

#eyecatch h1{
    font-size:clamp(30px, <?=$h1len?>vw, 50px);
}

#eyecatch h2{
    font-size:clamp(20px, <?=$h2len?>vw, 30px);
}

#eyecatch #period span{
    font-size:clamp(18px, 3vw, 24px);
} 

main section h2{
    background-color:<?=$h2color?>;
    color:<?=$h2text?>;
}

main section#venue{
    background-color:<?=$venuecolor?>;
}

main section#venue h3{
    color:<?=$h3text?>;
    border-color:<?=$h3text?>;
}

main p{
    color:<?=$ptext?>;
}

</style>

</head>
<body>
<div id="banaArea">
    <div class="close">✕</div>
    <div id="result"></div>
    <button id="save" class="btn">保存する</button>
</div>
<header>
    <div class="inner">
        <div id="exponame"><?=$name?></div>
        <div class="palette"><img src="img/palette.svg"></div>
        <a href="./expo.php" id="goback" class="btn">戻る</a>
    </div>
</header>
<div id="eyecatch" <?=$bgstyle?>>
    <div id="background" class="no-capture" data-name="photoUpload" data-title="背景画像設定"><img src="img/photo.svg"></div>
    <div id="eyecatchfilter">
    </div>

    <div class="inner">
        <div class="overlay">
        <h1 data-name="name" data-title="展示会タイトル"><?=$name?></h1>
        <h2 data-name="subtitle" data-title="サブタイトル"><?=$subtitle?></h2>

        <div id="period" class="period" data-name="period" data-title="開催期間設定" data-start="<?=date("Y-m-d", strtotime($start))?>" data-end="<?=date("Y-m-d", strtotime($end))?>">
            <span>開催期間：</span>
            <span class="year"><?=date("Y", strtotime($start))?>年</span>
            <span class="month"><?=date("n", strtotime($start))?>月</span>
            <span class="day"><?=date("j", strtotime($start))?>日</span>
            <span class="week">(<?=$week[date("w", strtotime($start))];?>)</span>
            <span> ～ </span>
            <span class="year"><?=date("Y", strtotime($end))?>年</span>
            <span class="month"><?=date("n", strtotime($end))?>月</span>
            <span class="day"><?=date("j", strtotime($end))?>日</span>
            <span class="week">(<?=$week[date("w", strtotime($end))];?>)</span>
        </div>

        <div id="organizers" class="organizers" data-name="organizers" data-title="主催者名">主催： <span><?=$organizers?></div>

        </div>
     </div>
     <div id="makebana" class="no-capture"><img src="../img/camera.svg" alt="バナー作成"><span id="bana">バナーを作成</span></div>
    </div>

<main>
<section id="about">
    <div class="inner">
        <h2>展示会について<div class="palette"><img src="img/palette.svg"></div></h2>
        <p class="about" data-name="description" data-title="展示会概要"><?=$description?></p>
    </div>
</section>

<section id="venue">
    <div class="inner">
        <h2>出展案内<div class="palette"><img src="img/palette.svg"></div></h2>

        <h3>カテゴリー</h3>
        <p class="category" data-name="category" data-title="カテゴリー追加" data-subid="<?=$category?>">
                <?php
                $sql = "SELECT * FROM category_summary WHERE vid = $id";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $cnt = $row['cnt'];
                    $name = $row['name'];
                    $cid = $row['category_id'];
                ?>
                <span data-cid="<?=$cid?>" data-type="edit" data-name="<?=$name?>" data-cot="<?=$cnt?>"><span class="name"><?=$name?></span><span class="cnt"><?=$cnt?></span></span>
                <?php } ?>
                <span data-cid="" data-type="new" data-name="new">カテゴリーを追加</span>
        </p>

        <h3>出展メリット</h3>
            <ul class="benefit" data-name="benefit" data-title="出展メリット">
            <?php
            foreach ($benefit_list as $b) {
                echo "<li>" . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . "</li>";
            }
            ?>
            </ul>
    </div>
</section>


<section id="exhibitors">
    <div class="inner">
        <h2>出展社一覧<div class="palette"><img src="img/palette.svg"></div></h2>
        <p></p>
</div>
</section>
</main>

<div id="editor">
    <div id="form">
        <h3>編集</h3>
        <div class="close">✕</div>
        <form method="POST" id="textedit">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <input type="hidden" name="name" id="name" value="" />
            <div class="box text">
                <input type="text" name="text" id="text" value="" />
            </div>
            <div class="box longlist">
                <textarea name="textarea" value="" id="longlist" /></textarea>
            </div>
            <div id="dates">
                <label>開催期間</label>
                <input type="date" id="start" name="start" value="" />
                <input type="date" id="end" name="end" value="" />
            </div>

            <div class="organizer_name">
                <input type="text" name="orgname" id="text2" value="" />
            </div>

            <div id="category">
                <input type="hidden" id="category_id" name="category_id" value="" />
                <input type="text" name="cdata" id="cdata" value="" placeholder="新規カテゴリー名"/>
                <div id="category_btn">
                    <button type="submit" name="submit" class="save" value="add">追加</button>
                    <button type="submit" name="submit" class="dell" value="dell">削除</button>
                    <button type="submit" name="submit" class="edit" value="edit">変更</button>
                </div>
            </div>
           <button type="submit" name="submit" value="edit">保存する</button>
        </form>

        <form action="upload.php" id="photoUpload" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$id?>" />
            <input type="file" name="photo" accept="image/*">
            <button type="submit">背景画像をアップロード</button>
        </form>
    </div>
</div>


<div id="colorbar">
<div class="close">✕</div>
<form method="POST">

<div>
        <label>
            <span>コンテンツ背景</span>
            <input type="color" id="maincolor" data-name="maincolor" name="main" value="<?=$maincolor?>" />
        </label>

        <label>
            <span>テキスト色</span>
            <input type="color" id="ptext" data-name="ptext" name="p" value="<?=$h2color?>" />
        </label>
    </div>

    <div>
        <label>
            <span>出展エリア背景</span>
            <input type="color" id="venuecolor" data-name="venuecolor" name="venue" value="<?=$venuecolor?>" />
        </label>

       <label>
            <span>出展小見出し色</span>
            <input type="color" id="h3text" data-name="h3text" name="h3text" value="<?=$h3text?>" />
        </label>
    </div>

    <div>
        <label>
            <span>ヘッダー背景</span>
            <input type="color" id="headercolor" data-name="headercolor" name="header" value="<?=$headercolor?>" />
        </label>

        <label>
            <span>ヘッダーテキスト色</span>
            <input type="color" id="headertext" data-name="headertext" name="headertext" value="<?=$headertext?>" />
        </label>
    </div>

    <div>
        <label>
            <span>大見出し背景</span>
            <input type="color" id="h2color" data-name="h2color" name="h2" value="<?=$h2color?>" />
        </label>

       <label>
            <span>大見出しテキスト色</span>
            <input type="color" id="h2text" data-name="h2text" name="h2text" value="<?=$h2text?>" />
        </label>
    </div>

</form>

</div>

<footer>
    <div class="inner">
        <div id="copyright">&copy;3DVenue.</div>
    </div>
</footer>

<script src="../common/js/jquery.js"></script>
<script src="../common/js/dom-to-image.min.js"></script>
<script type="text/javascript">

$(function(){

    $('#eyecatch h1,' +
      '#eyecatch h2,' + 
      '#period,' + 
      '#organizers,' + 
      '#about .about,' + 
      '#venue .category,' + 
      '#venue .benefit,' + 
      '#background' ).on('click', function() {
            let name = $(this).data('name');
            let title = $(this).data('title');
            clear();
            $('input#name').val(name);
            $('#editor').addClass('active');
            $('#form h3').text(title);
            $('#form').addClass(name);
    });

    $('main section h2,' + 
    'header #exponame,' + 
    '#butinuki,' + 
    '#maincolor,' +
    'header .palette' ).on('click', function() {
        $('#colorbar').addClass('active');
        $('body').addClass('colorbar');
    });

    $('.category span').on('click',function(){
        $('#category_btn').removeClass();
        let cid = $(this).data('cid');
        let name = $(this).data('name');
        let type = $(this).data('type');
        $('#form #category_id').val(cid);
        $('#form #cdata').val(name);
        $('#form').addClass(cid);
        $('#category_btn').addClass(type);
        })

    $('#about .about').on('click',function(){
        let text = $(this).html();
        $('#longlist').val(text);
    })

    $('h1,h2').on('click',function(){
        let text = $(this).html();
        $('#text').val(text);
    })

    $('#venue .category').on('click',function(){
        let text = $(this).html();
    })

    $('#venue .benefit').on('click',function(){
        const list = $(this).find('li').map(function() {
         return $(this).text(); 
        }).get();

        $('#longlist').val(list.join('\n'));
    })

    $('#period').on('click',function(){
        let start = $(this).data('start');
        let end = $(this).data('end');
        $('input#start').val(start);
        $('input#end').val(end);
    })

    $('#organizers').on('click',function(){
        let text = $(this).find('span').html();
        $('#text').val(text);
    })

    $('#form .close,#colorbar .close').on('click', function() {
        clear();
        $('#editor,#colorbar').removeClass('active');
        $('body').removeClass('colorbar');
    });

    function clear(){
        $('#text,#longlist,#dates input').val('');
        $('#form').removeClass();
    }

    $('#maincolor').on('input',function(){
        let bgcolor = $(this).val();
        $('body').css({'background-color':bgcolor});
    })

    $('#ptext').on('input',function(){
        let bgcolor = $(this).val();
        $('main p').css({'color':bgcolor});
    })

    $('#h3text').on('input',function(){
        let bgcolor = $(this).val();
        $('#venue h3').css({'color':bgcolor,'border-color':bgcolor});
    })

    $('#venuecolor').on('input',function(){
        let bgcolor = $(this).val();
        $('section#venue').css({'background-color':bgcolor});
    })

    $('#headercolor').on('input',function(){
        let bgcolor = $(this).val();
        $('header').css({'background-color':bgcolor});
    })

    $('#headertext').on('input',function(){
        let bgcolor = $(this).val();
        $('header').css({'color':bgcolor});
    })

    $('#h2color').on('input',function(){
        let bgcolor = $(this).val();
        $('main section h2').css({'background-color':bgcolor});
    })

    $('#h2text').on('input',function(){
        let bgcolor = $(this).val();
        $('main section h2').css({'color':bgcolor});
    })

    $('#colorbar input').on('change',function(){
        let name = $(this).data('name');
        let color = $(this).val();        
        colorChange(name,color);
    })

    function colorChange(name, color) {
        console.log(name);
        console.log(color);
        console.log('<?=$id?>');
        $.ajax({
            url: 'updatecolor.php',
            type: 'POST',
            data: {
                id: '<?=$id?>',
                name: name,
                color: color
            },
            success: function(response) {
                console.log('更新成功:', response);
            },
            error: function(xhr, status, error) {
                console.log('エラー:', error);
            }
        });
    }


    $('#banaArea .close').on('click',function(){
        $('#result').empty();
        $('#banaArea').removeClass();
    })

    $('#makebana').on('click', function () {
        $('#banaArea').addClass('view');
        setTimeout(function(){
            $('#banaArea').addClass('view');
            MakeBana();
        },500)
    });

    function MakeBana(){
    $('#eyecatch').addClass('view');
        const node = $('#eyecatch').get(0);
        domtoimage.toPng(node)
        .then(function (dataUrl) {
            $('#result').append('<img src="'+dataUrl+'">');
            $('#eyecatch').removeClass('view');
        })
        .catch(function (error) {
            alert('failed.');
        });
    };


    $('#save').on('click', function () {
      const img = $('#result img').attr('src'); // dataURL
      $.post('save.php', {
        image: img,
        id: <?=$id?>
      }, function (res) {
        $('#banaArea').removeClass('view');
        console.log(res);
      });
    });

});
</script>
</body>
</html>