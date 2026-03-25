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
?>

</html>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="icon" href="../favicon.ico">
    <title>主催者マイページ</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
<div class="inner">
<h1>トップページ</h1>

<h2>お知らせ</h2>
<section>
<div id="info">
<?php
    $sql = "SELECT * FROM infomation WHERE target = 2";
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
</div>
</main>
<?php include_once 'footer.php'; ?>
</body>
</html>