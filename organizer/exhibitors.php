<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
// $oid = $_SESSION['oid'];
include_once "../config.php";

if (isset($_GET['vid'])) {
    $vid = $_GET['vid'];
    $sql = "SELECT name FROM venue WHERE id = ? AND organizer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $vid,$oid);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        $_SESSION['vid'] = $vid;
        $_SESSION['vname'] = $row['name'];
        header('Location: exhibitors.php');
        exit;
    }else{
        header('Location: expo.php');
        exit;
    }

}else {
    if (!isset($_SESSION['vid'])) { header('Location: expo.php'); exit; }
    $vid = $_SESSION['vid'];
    $vname = $_SESSION['vname'];
}

    $sql = "SELECT * FROM category_summary WHERE vid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vid);
    $stmt ->execute();
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/exhibitors.css">
    <link rel="icon" href="../favicon.ico">
    <title>出展者一覧</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>

<main>
<div class="inner">
<h1>出展者一覧</h1>
<section id="expo">
<h2><?=$vname?></h2>

<div id="memo">a:=アクセス c:=クリック</div>

<div class="exhibitors">

<div id="companies">
<?php
    foreach ($categories as $cat) {
    $name = $cat['name'] ?? '';
    $catid = $cat['category_id'] ?? 0;
    $id = $cat['vid'] ?? 0;
?>
<h3><?=$name?></h3>

<div class="cardbox">
<table>
    <tr>
        <th class="company">会社名</th>
        <th class="titile">タイトル</th>
        <th class="address">住所</th>
        <th class="web">Web</th>
        <th class="access">アクセス</th>
        <th class="click">クリック</th>
    </tr>
    <?php
        $sql = "SELECT * FROM exhibitors JOIN company ON exhibitors.cid = company.cid WHERE vid = ? AND category = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id,$catid);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $company = $row['company'];
            $url = $row['url'];
            $title = $row['title'];
            $zip = $row['zip'];
            $prefecture = $row['prefecture'];
            $address1 = $row['address1'];
            $click = $row['click'];
            $access = $row['access'];
    ?>
    <tr>
        <td class="company"><?=$company?></td>
        <td class="title"><?=$title?></td>
        <td class="address"><?=$prefecture?><?=$address1?></td>
        <td class="web" data-web="<?=$url?>">🌎</td>
        <td class="access"><?=$access?></td>
        <td class="click"><?=$click?></td>
    </tr>
    <?php }?>
   </table>
</div>

    <?php }?>
    </div>
</div>
</section>
</div>
</main>
<div id="web">
<div class="close">✕</div>
<iframe src="about:blank"></iframe>
</div>
<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

    $('table td.web').on('click',function(){
        var url = $(this).data('web');
        $('#web').addClass('active');
        $('#web iframe').attr('src',url);
    })

    $('#web .close').on('click',function(){
        $('#web').removeClass('active');
    })

    });
</script>
</body>
</html>