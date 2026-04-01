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

$expo = "comapny"; // view select
$vid = 0;
$vname = "出展社一覧";
$title = "出展社管理";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submit = $_POST['submit'] ?? ''; 
    $cid = (INT)$_POST['cid'] ?? '0';
    switch ($submit) {
      case 'yes': 
            $sql = "UPDATE company SET status = 1 WHERE cid = '$cid'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
          break;

      case 'ban': 
            $sql = "UPDATE company SET status = -1 WHERE cid = '$cid'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
          break;

      case 'off': 
            $sql = "UPDATE company SET status = 0 WHERE cid = '$cid'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
          break;
    }  

    exit;
}

if (isset($_GET['vid']) && $_GET['vid'] !== "") {
    $vid = (int)($_GET['vid'] ?? 0);
    $expo = "venue";
    $title = "参加企業一覧";
    
    if (isset($_GET['vname']) && $_GET['vname'] !== "") {
        $vname = $_GET['vname'];
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/company.css">
    <link rel="icon" href="../favicon.ico">
    <title>出展社管理</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h2><?=$title?></h2>
<section id="companies">
<span id="add">+</span>
<h3><?=$vname?> <a class="btn" href="../mypage" target="_mypage">主催者ページ</a></h3>
<div id="view"><span class="view">停止中を表示</span></div>
<table>
    <tr>
        <th>企業名</th>
        <th>担当者名</th>
        <th>電話番号</th>
        <th>所在地</th>
        <th>設定</th>
    </tr>
<?php
    $sql = "SELECT * FROM company ORDER BY status";
    
    if($expo == "venue"){
        $sql = "SELECT * FROM company JOIN exhibitors ON company.cid = exhibitors.cid WHERE vid = {$vid} ORDER BY status";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cid = $row['cid'];
        $company = $row['company'];
        $name = $row['name'];
        $telno = $row['telno'];
        $prefecture = $row['prefecture'];
        $address1 = $row['address1'];
        $address2 = $row['address2'];
        $status = $row['status'];
?>

<tr data-cid="<?=$cid?>" data-status="<?=$status?>" class="s<?=$status?>">
    <td class="companyname"><?=$company?></td>
    <td><?=$name?></td>
    <td><?=$telno?></td>
    <td><?=$prefecture?><?=$address1?><?=$address2?></td>
    <td class="set"><div class="setbox s<?=$status?>"><span class="yes">有効</span><span class="ban">停止</span><span class="off">解除</span></div></td>
</tr>
<?php } ?>
</table>

</section>
</div>
</main>

<div id="companyedit">
    <div id="check">
        <div class="close">✕</div>
        <h3 id="companyname"></h3>
        <p>マイページにアクセスします。</p>
        <input type="hidden" name="cid" id="cid" />
        <div id="button"><span class="btn" id="mypage">マイページを開く</span></div>        
    </div>
</div>

<div id="frame">
        <div class="close">✕</div>
        <div id="iframetitile">企業専用ページ</div>
        <iframe src="../login.php"></iframe>
</div>

<?php include_once '../footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){


        $('#companies table tr').on('click', function() {
            $('#companyedit').addClass('active');
            $('#companyedit input').val('');
            const cid = $(this).data('cid');
            const cname = $(this).find('td.companyname').text();
            $('#cid').val(cid);
            $('#companyname').text(cname);
        });

        $('#mypage').on('click', function() {
            const id = $('#cid').val();
            $('#frame iframe').attr('src','jump_mypage.php?id=' + id);
            $('#frame').addClass('active');
            $('#companyedit').removeClass();
            $('#companies').addClass('link');
        });

        $('#add').on('click', function() {
            $('#frame iframe').attr('src','../acount.php');
            $('#frame').addClass('active');
            $('#companyedit').removeClass();
        });

        $('#check .close').on('click',function(){
            $('#companyedit').removeClass('active');
            $('#companyedit input').val('');
        });

        $('#companies table .setbox span').on('click',function(e){
            e.stopPropagation(); 
            let c = $(this).attr('class');
            let cid = $(this).parents('tr').data('cid');
            $.post('company.php', {
                submit:c,
                cid:cid
            },function(data) {
                window.location.href = 'company.php';
            })
        })

        $('.view').on('click',function(){
            $('#companies table').toggleClass('view');
        })

        $('#frame .close').on('click',function(){
            $('#frame').removeClass('active');           
        })

});
</script>
</body>
</html>