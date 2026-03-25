<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

require_once('auth.php');
$title="バーチャル展示会 EXPO 2026";
require_once "../config.php";
$week = ['日', '月', '火', '水', '木', '金', '土'];
$categories = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['id']) || $_GET['id'] === '') {
        header("Location: index.php");
        exit;
    }

    $id = $_GET['id'];
    // ここから処理

    $id = $_GET['id'];
    $sql = "SELECT * FROM venue WHERE id = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // ★ ここがポイント：1回だけ fetch して判定 
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: index.php");
        exit;
    }

        $bgstyle = "";
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

        $background = $row['background'];
        $maincolor = $row['maincolor'];
        $ptext = $row['ptext'];
        $h3text = $row['h3text'];
        $venuecolor = $row['venuecolor'];
        $headercolor = $row['headercolor'];
        $headertext = $row['headertext'];
        $h2color = $row['h2color'];
        $h2text = $row['h2text'];


        if(!$background == ''){
            $bgstyle = 'style="background-image:url(../expo/'.$id.'/bg.'.$background.')"';
        }

    }else{
        header("Location: index.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="../expo/css/style.css">
    <link rel="stylesheet" type="text/css" href="../expo/css/expo.css">
    <link rel="icon" href="../favicon.ico">
    <title>
        <?=$title?>
    </title>
<style type="text/css">
header{
    background-color:<?=$headercolor?>;
    color:<?=$headertext?>;
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
}

main p{
    color:<?=$ptext?>;
}

</style>

</head>
<body>
<header>
    <div class="inner">
        <?=$name?>
    </div>
</header>
<div id="eyecatch" <?=$bgstyle?>>
    <div id="eyecatchfilter">
    </div>

    <div class="inner">
        <div class="overlay">
        <h1 data-name="name" data-title="展示会タイトル"><?=$name?></h1>
        <div>
        <h2 data-name="subtitle" data-title="サブタイトルや英語表記など"><?=$subtitle?></h2>
            <div id="period" class="period" data-name="period" data-title="開催期間" data-start="<?=date("Y-m-d", strtotime($start))?>" data-end="<?=date("Y-m-d", strtotime($end))?>">
                <p>【会期/Date】</p>
                <div class="date">
                    <p class="year"><?=date("Y年", strtotime($start))?></p>
                    <p class="month"><?=date("n", strtotime($start))?></p>月
                    <p class="day"><?=date("j", strtotime($start))?></p>日
                    <p class="week"><span><?=$week[date("w", strtotime($start))];?></span></p>
                </div>
                <div>－</div>
                <div class="date">
                    <p class="year"><?=date("Y年", strtotime($end))?></p>
                    <p class="month"><?=date("n", strtotime($end))?></p>月
                    <p class="day"><?=date("j", strtotime($end))?></p>日
                    <p class="week"><span><?=$week[date("w", strtotime($end))];?></span></p>
                </div>
            </div>
        </div>
        <div id="organizer">主催：3DVenue</div>
        </div>
     </div>
    </div>

<main>
<section id="about">
    <div class="inner">
        <h2>ABOUT</h2>
        <p class="about" data-name="description" data-title="展示会概要"><?=$description?></p>
    </div>
</section>

<section id="venue">
    <div class="inner">
        <h2>出展情報（Exhibitors）</h2>

        <h3>出展分野</h3>
        <p class="category" data-name="category" data-title="展示会分野（カテゴリ）" data-subid="<?=$category?>">
            <?php
            $categoryNames = []; // ← ここで配列を用意

            foreach ($categories as $subid) {
                    $sql = "SELECT name FROM subcategory WHERE subid = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $subid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // $row = $result->fetch_assoc();
                    if ($row = $result->fetch_assoc()) {
                     $categoryNames[$subid] = $row['name']; // ← 配列に保存 
                    ?>
            <span><?=$row['name']?></span>
            <?php } } ?>
        </p>
        <h3>出展メリット</h3>
            <ul class="benefit" data-name="benefit" data-title="展示会のメリット">
            <?php
            foreach ($benefit_list as $b) {
                echo "<li>" . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . "</li>";
            }
            ?>
            </ul>                                    

        <h3>出展料金</h3>
        <div>
            <span id="zero">0</span><span id="yen">円</span><br />
            <span>完全無料</span>
        </div>
    </div>
</section>


<section id="exhibitors">
    <div class="inner">
        <h2>出展社一覧</h2>
    <div id="companies">
        <?php
            foreach ($categories as $subid) {

                $sql = "SELECT company.company AS company FROM  exhibitors JOIN company ON exhibitors.cid = company.cid WHERE vid = ? AND category = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $id, $subid); // 両方int型なら "ii"
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
        ?>
            <span class="category"><?=$categoryNames[$subid]?></span>
        <?php
             while ($row = $result->fetch_assoc()) {
        ?>
            <span class="cname"><?=htmlspecialchars($row['company'])?></span>
        <?php
               }
             }
          }
        ?>
    </div>

</div>
</section>
</main>

<footer>
    <div class="inner">
        <div id="copyright">&copy;3DVenue.</div>
    </div>
</footer>

<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
$(function(){

    $('#eyecatch h1,' +
      '#eyecatch h2,' + 
      '#period,' + 
      '#about .about,' + 
      '#venue .category,' + 
      '#venue .benefit,#background' ).on('click', function() {
            let name = $(this).data('name');
            let title = $(this).data('title');
            clear();
            $('input#name').val(name);
            $('#editor').addClass('active');
            $('#form h3').text(title);
            $('#form').addClass(name);
    });

    $('main section h2,' + 
    'header,' + 
    '#butinuki,' + 
    '#maincolor' ).on('click', function() {
        $('#colorbar').addClass('active');
        $('body').addClass('colorbar');
    });

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
        // $('#longlist').val(text);
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
        $('#venue h3').css({'color':bgcolor});
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


    $('#background').on('click',function(){

    })


});
</script>
</body>
</html>
