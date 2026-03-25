<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
if (!empty($_POST['image']) && isset($_POST['id'])) {

    $id = intval($_POST['id']);
    $img = $_POST['image'];

    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);

    $data = base64_decode($img);

    $dir = '../expo/img/';


    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $filename = $dir . 'bana' . $id . '.png';

    file_put_contents($filename, $data);

    echo 'saved: bana' . $id . '.png';
}

?>
