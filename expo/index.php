<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
include_once "../config.php";

$own = $_SERVER['REQUEST_URI'];
if (preg_match('/\/[0-9]+$/', $own)) {
    header("Location: " . $own . "/", true, 301);
    exit;
}

// 曜日を日本語表記に変更
$week = ['日', '月', '火', '水', '木', '金', '土'];
$categories = [];
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['i']) || $_GET['i'] === '') {
        header("Location: index.php");
        exit;
    }

$id = $_GET['i'];

$logDir = "../que/{$id}";
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logfile = "{$logDir}/" . 'access.log';
$log = date('Y-m-d H:i:s') . ' ' . $_SERVER['REMOTE_ADDR'] . "\n";
file_put_contents($logfile, $log, FILE_APPEND);


$_SESSION['expoid'] = $id;

    $sql = "SELECT * FROM venue JOIN organizer ON venue.organizer = organizer.oid WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();



    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: /index.php");
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
        $organizers = $row['organizers'];
        $benefit = str_replace(["\r\n", "\r"], "\n", $benefit);
        $benefit_list = explode("\n", $benefit);
        $categories = explode(',', $category);

        $company = $row['company'];
        $background = $row['background'];
        $maincolor = $row['maincolor'];
        $ptext = $row['ptext'];
        $h3text = $row['h3text'];
        $venuecolor = $row['venuecolor'];
        $headercolor = $row['headercolor'];
        $headertext = $row['headertext'];
        $h2color = $row['h2color'];
        $h2text = $row['h2text'];

        $oname = $row['oname'];

        if(!$background == ''){
            $bgstyle = 'style="background-image:url(../../que/'.$id.'/top.webp)"';
        }

    }else{
        header("Location: index.php");
        exit;
    }

    $namacount = mb_strlen($name);
    $subcount = mb_strlen($subtitle);
    $orgcount = mb_strlen($organizers);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <meta name="description" content="<?=$description?>">
    <link rel="icon" href="../favicon.ico">
    <title><?=$name?> | 3DVenue</title>
<style type="text/css">
header{
    background-color:<?=$headercolor?>;
    color:<?=$headertext?>;
}

#eyecatch h1{
    font-size:clamp(30px,calc(100vw / <?=$namacount?>),55px);
}

#eyecatch h2{
    font-size:clamp(16px,calc(100vw / <?=$subcount?>),30px);
}

#eyecatch #period{
    font-size:clamp(14px,calc(100vw / 40),24px);
}

#eyecatch #organizer{
    font-size:clamp(14px,calc(100vw /(<?=$orgcount?> + 12) ),24px);
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
    <a href="../venue.php" id="enter" class="btn">会場に入る</a>
    <div id="eyecatchfilter">
    </div>
    <div class="inner">
        <div class="overlay">
        <h1 data-name="name" data-title="タイトル"><?=$name?></h1>
        <div>
        <h2 data-name="subtitle" data-title="サブタイトル"><?=$subtitle?></h2>

        <div id="period" class="period" data-name="period" data-title="開催期間" data-start="<?=date("Y-m-d", strtotime($start))?>" data-end="<?=date("Y-m-d", strtotime($end))?>">
            <span>開催期間：</span>
            <span class="month"><?=date("n", strtotime($start))?>月</span>
            <span> </span>
            <span class="day"><?=date("j", strtotime($start))?>日</span>
            <span>,</span>
            <span class="year"><?=date("Y", strtotime($start))?>年</span>
            <span class="week">(<?=$week[date("w", strtotime($start))];?>)</span>
            <span> ～ </span>
            <span class="month"><?=date("n", strtotime($end))?>月</span>
            <span> </span>
            <span class="day"><?=date("j", strtotime($end))?>日</span>
            <span>,</span>
            <span class="year"><?=date("Y", strtotime($end))?>年</span>
            <span class="week">(<?=$week[date("w", strtotime($end))];?>)</span>
        </div>

        </div>
        <div id="organizer">主催： <?=$organizers?></div>
        </div>
     </div>
    </div>

<main>
<section id="about">
    <div class="inner">
        <h2>展示会について</h2>
        <p class="about" data-name="description" data-title="展示会概要"><?=$description?></p>
    </div>
</section>

<section id="venue">
    <div class="inner">
        <h2>出展案内</h2>

        <h3>カテゴリー</h3>
        <p class="category" data-name="category" data-title="カテゴリー追加" data-subid="<?=$category?>">
                <?php
                $category_map = [];
                $sql = "SELECT * FROM category_summary WHERE vid = $id";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $cnt = $row['cnt'];
                    $name = $row['name'];
                    $cid = $row['category_id'];
                    $category_map[$cid] = $name;
                ?>
                <span data-cid="<?=$cid?>" data-type="edit" data-name="<?=$name?>" data-cot="<?=$cnt?>"><span class="name"><?=$name?></span></span>
                <?php } ?>
        </p>
        <h3>出展メリット</h3>
            <ul class="benefit" data-name="benefit" data-title="展示会のメリット">
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
        <h2>出展社一覧</h2>
    <div id="companies">
    <div id="elist">
    <?php foreach ($category_map  as $cid => $name) { ?>
        <details>
        <summary data-cid="<?=$cid?>"><?= $name ?></summary>
        <p class="elist">
        <?php
        $sql = "SELECT * FROM exhibitors JOIN company ON exhibitors.cid = company.cid WHERE category = {$cid}";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $company = $row['company'];
            ?>
        <span><?=$company?></span>
        <?php } ?>
        </p>
    </details>
    <?php } ?>
    </div>

    </div>

</div>
</section>
</main>

<footer>
    <div class="inner">
        <div id="copyright">&copy;3DVenue.</div>
    </div>
</footer>

<script src="../../common/js/jquery.js"></script>
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

    let parentUrl = document.referrer;
    if(parentUrl == 'https://venue.3dvenue.jp/mypage/'){
        $('body').css({'pointer-events':'none'});
    }

});
</script>
</body>
</html>