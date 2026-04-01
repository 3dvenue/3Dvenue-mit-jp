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
    <link rel="stylesheet" type="text/css" href="./common/css/index.css">
    <link rel="icon" href="./favicon.ico">
    <title>3DVenue - バーチャル展示会場構築エンジン（MITライセンス）</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<div id="eyecatch">
    <div class="inner">
        <div class="overlay">
        <h1>あなたのホームページを<br />
            バーチャル展示会への入口に。
        </h1>
            <p>
            展示会構築の為のシステムをMITライセンスで公開しました。<br/>
            中小規模事業者のための新しいチャンス。<br>
            ホームページを展示ブースへと変える、負担の少ない仕組み。<br>
            使い方次第で様々な用途にご利用いただけると思います。
        </p>
        </div>
        <div id="eycatchCenter">
        <a href="acount.php">出展者登録</a>
        <a href="https://github.com/3dvenue/3Dvenue-mit-jp" target="_blank">Githubからダウンロード</a>
        </div>
     </div>
</div>

<main>
    <div class="inner">

    <section id="infomation">
    <h2>お知らせ</h2>
        <div id="info">
        <?php
            $sql = "SELECT * FROM infomation WHERE target = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            $published_at = date('Y年n月j日',strtotime($row['published_at']));
            $title = $row['title'];
            $content = $row['content'];
        ?>
        <details>
            <summary><date><?=$published_at?>：</date><?=$title?></summary>
            <div><?=$content?></div>
        </details>
        <?php } ?>
        </div>
    </section>

<section id="content">
    <h2>コンテンツ</h2>
    <div id="features">
        <div><figure><a href="about.php"><img src="./img/about.webp" alt="3DVenueバーチャル展示会とは"></a></figure></div>
        <div><figure><a href="expo.php"><img src="./img/exhibitors.webp" alt="公開中の3DVenueバーチャル展示会"></a></figure></div>
    </div>
</section>

</div>


<section id="venue">
    <div class="inner">
    <h2>現在開催中の展示会</h2>
        <div>
        <ul id="venues">
        <?php
            $sql = "SELECT * FROM venue WHERE public = 1 ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
        ?>
        <li><figure><a href="./expo/<?=$id?>/" target="_blank"><img src="./que/<?=$id?>/bana.webp"></a></figure></li>
        <?php } ?>
        </ul>
        </div>
    </div>
</section>

</main>
<?php include_once('footer.php')?>
</body>
</html>