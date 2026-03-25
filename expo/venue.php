<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
if (empty($_SESSION['expoid'])) {
    header("Location: /index.php");
    exit;
}else{
    $id = $_SESSION['expoid'];
    include_once "../config.php";
}

$logDir = __DIR__ ."/{$id}";
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$logfile = "{$logDir}/" . 'venue.log';
$log = date('Y-m-d H:i:s') . ' ' . $_SERVER['REMOTE_ADDR'] . "\n";
file_put_contents($logfile, $log, FILE_APPEND);

    $bana = "../expo/img/bana".$id.".png";

    $sql = "SELECT * FROM venue WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: /index.php");
        exit;
    }

    $name = $row['name'];
    $subtitle = $row['subtitle'];
    $description = $row['description'];
    $category = $row['category'];
    $benefit = $row['benefit'];

    $sql = "SELECT 
        company,
        title,
        subtitle,
        company.
        telno AS telno,
        description,
        category,
        url,
        vid,
        id,
        exhibitors.cid AS cid,
        zip,
        prefecture,
        address1,
        address2,
        logo
     FROM exhibitors 
     JOIN company ON exhibitors.cid = company.cid 
     WHERE vid = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exhibitors = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../common/css/base.css">
<link rel="stylesheet" type="text/css" href="../expo/css/style.css">
<link rel="stylesheet" type="text/css" href="../expo/css/venue.css">
<link rel="icon" href="../favicon.ico">
<title>3D EXPO - 展示会場</title>
</head>
<body>
<canvas id="bg-canvas"></canvas>

<nav id="categories">
<div class="inner">
<ul>
<?php
    $sql = "SELECT * FROM category_summary WHERE vid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()){
        $c_id = $row['category_id'];
        $name = $row['name'];
        $cnt = $row['cnt']; //出展数
?>
<li class="c<?=$cnt?>" data-id="<?=$c_id?>"><?=$row['name']?></li>
<?php } ?>
</ul>
</div>
</nav>

<div id="ev">
    <button id="down" title="下のフロアへ"><img src="../img/down.svg?t=1"></button>
    <button id="right" title="右回転"><img src="../img/left.svg?t=1"></button>
    <button id="stop" title="回転停止"><img src="../img/stop.svg?t=1"></button>
    <button id="left" title="左回転"><img src="../img/right.svg?t=1"></button>
    <button id="up" title="上のフロアへ"><img src="../img/up.svg?t=1"></button>
</div>

<div id="booth">
    <div id="boothBox">
        <div class="close">&times;</div>
        <div id="boothheader">
            <div id="logo"><img src="" alt="企業ロゴ"></div>
            <div id="companyname">企業名</div>
        </div>

        <div id="boothmain">
            <h2 id="title">キャッチコピー</h2>
            <figure><img id="image" src="" alt="展示イメージ"></figure>
            <h3 id="subtitle">サブタイトル</h3>
            <div id="btnBox"><a href="" id="url" class="btn" target="_blank">詳細を見る</a></div>
            <div id="description">
                <div class="boothinner">
                    <h2>出展概要</h2>
                    <p></p>
                </div>
            </div>
        </div>

        <div id="boothfooter">
            <div class="boothinner">
                <p id="company">企業名</p>
                <p id="zip">郵便番号: <span></span></p>
                <p id="address">住所</p>
                <p id="telno">電話番号: <span></span></p>
            </div>
        </div>
    </div>
</div>

<script src="../common/js/jquery.js"></script>
<script type="importmap">
{
  "imports": {
    "three": "../js/three/three.module.js",
    "three/addons/": "../js/three/addons/"
  }
}
</script>

<script type="module">
import * as THREE from "three";
import { OrbitControls } from "three/addons/controls/OrbitControls.js";
import { GLTFLoader } from "three/addons/loaders/GLTFLoader.js";
import { Reflector } from 'three/addons/objects/Reflector.js';

const exhibitors = <?php echo json_encode($exhibitors, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>;

const mouse = new THREE.Vector2();
const raycaster = new THREE.Raycaster();

$(function(){

const canvas = document.getElementById('bg-canvas');
const renderer = new THREE.WebGLRenderer({
    canvas,
    antialias: true,
    alpha: true
});
renderer.setClearColor(0x000000, 0);

/* 解像度設定 */
renderer.setPixelRatio(window.devicePixelRatio);
renderer.setSize(window.innerWidth, window.innerHeight);

renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.5;
renderer.outputEncoding = THREE.sRGBEncoding;

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);

/* カメラ初期位置 */
camera.position.set(0, 0.0, 0.1);

const controls = new OrbitControls(camera, renderer.domElement);
controls.target.set(2.0, 1.0, 0.0);
controls.update();

controls.minDistance = 1;
controls.maxDistance = 4;

const light = new THREE.AmbientLight(0xCCCCCC, 2.5);
scene.add(light);

// 反射床追加
const circleGeo = new THREE.CircleGeometry(10, 64);
const reflector = new Reflector(circleGeo, {
    clipBias: 0.003,
    textureWidth: window.innerWidth * window.devicePixelRatio,
    textureHeight: window.innerHeight * window.devicePixelRatio,
    color: 0xffffff
});
reflector.rotation.x = -Math.PI / 2;
reflector.position.set(0, -0.5, 0);
reflector.name = "reflector";
scene.add(reflector);

// 丸床追加
const circlefloor = new THREE.CircleGeometry(10, 64);
const circleMat = new THREE.MeshStandardMaterial({
    color: 0x000000,
    transparent: true,
    roughness: 0.5,
    metalness: 0.0,
    opacity: 0.7,
    side: THREE.DoubleSide
});

const circle = new THREE.Mesh(circlefloor, circleMat);
circle.rotation.x = -Math.PI / 2; 
circle.position.set(0, -0.49, 0);  // 反射床と重ならないよう微調整
circle.name = "floor";
scene.add(circle);

// 展示会バナー追加
const loader = new THREE.TextureLoader();
loader.load('<?=$bana?>', (texture) => {
    texture.colorSpace = THREE.SRGBColorSpace;
    texture.magFilter = THREE.LinearFilter;
    texture.minFilter = THREE.LinearMipmapLinearFilter;
    texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
    
    omaterial.map = texture;
    omaterial.needsUpdate = true;
});

const ogeometry = new THREE.PlaneGeometry(0.8 * 1.8, 0.8);
const omaterial = new THREE.MeshBasicMaterial({ 
    transparent: true,
    opacity:0
    });
const bana = new THREE.Mesh(ogeometry, omaterial);

bana.position.set(2.0,0.05,0);
bana.rotation.y = Math.PI / 2;
bana.name = "bana";
scene.add(bana);

const radius = 2;
let floor = 0;
const heightStep = 0.9;
const panelMeshes = [];

let checkCount = 0;

function makePanel(id){
    checkCount = 0;

    panelMeshes.forEach(mesh => {
        scene.remove(mesh);
        mesh.geometry.dispose();
        mesh.material.dispose();
    }); 

    controls.reset();
    panelMeshes.length = 0;
    const filtered = exhibitors.filter(item => item.category == id);
    const count = filtered.length;

    checkCount = count;

    const panelSize = 0.8;

    for (let i = 0; i < count; i++) {
        const data = filtered[i];
        const loader = new THREE.TextureLoader();
        const imgid = data.cid;
        const url = '../expo/<?=$id?>/' + imgid + '.jpg';

        loader.load(url, (LogoTexture) => {
            LogoTexture.colorSpace = THREE.SRGBColorSpace;
            renderer.outputColorSpace = THREE.SRGBColorSpace;
            
            LogoTexture.magFilter = THREE.LinearFilter;
            LogoTexture.minFilter = THREE.LinearMipmapLinearFilter;
            LogoTexture.anisotropy = renderer.capabilities.getMaxAnisotropy();
            LogoTexture.needsUpdate = true;

            const imgAspect = LogoTexture.image.width / LogoTexture.image.height;
            const planeHeight = panelSize;
            const planeWidth = panelSize * imgAspect;

            const geometry = new THREE.PlaneGeometry(planeWidth, planeHeight);
            const material = new THREE.MeshBasicMaterial({
                map: LogoTexture,
                transparent: true,
                opacity:0.95
            });

            const mesh = new THREE.Mesh(geometry, material);
            const angleDeg = (i % 10) * 36 - 90;
            const y = Math.floor(i / 10) * heightStep;

            if (i % 10 === 0) floor++;

            placePanel(mesh, angleDeg, y);
            mesh.lookAt(0, y, 0);
            mesh.name = "card"+i;
            mesh.visible = true;
            mesh.userData = { cid:imgid, y:y };
            mesh.scale.set(1,1,1);

            scene.add(mesh);
            panelMeshes.push(mesh);
        });
    }
}

function placePanel(mesh, angleDeg, y) {
    const rad = angleDeg * Math.PI / 180;
    const x = Math.cos(rad) * radius;
    const z = Math.sin(rad) * radius;
    mesh.position.set(x, y, z);
}

window.addEventListener('resize', () => {
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
});

const angleToCamera = Math.atan2(camera.position.x - bana.position.x, camera.position.z - bana.position.z);

$('#categories li').on('click',function(){
    $('#categories li').removeClass('select');
    $(this).addClass('select');
    $('#ev').addClass('active');
    const id = $(this).data('id');

    camera.position.z = 0.1;
    scene.remove(bana);
    bana.geometry.dispose();
    bana.material.dispose();

    makePanel(id);

    $('#ev').removeClass('under');
    if(checkCount <= 9) $('#ev').addClass('under');
})

let f = 0;

$('#right').on('click',function(){
    controls.autoRotate = true;
    controls.autoRotateSpeed = 2.0;
});

$('#left').on('click',function(){
    controls.autoRotate = true;
    controls.autoRotateSpeed = -2.0;
});

$('#stop').on('click',function(){
    controls.autoRotate = false;
})

$('#up').on('click',function(){
    if(f < (floor - 1)){
        f++;
        targetY = f * heightStep; 
    }
})

$('#down').on('click',function(){
    if(f >= 1){
        f--;
        targetY = f * heightStep;
    }
})

$(window).on('click pointerdown', (event) => {
    if (event.target.id !== 'bg-canvas') return;

    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(panelMeshes, true);

    if (intersects.length > 0) {
        const obj = intersects[0].object;
        let cid = obj.userData.cid;
        cardSet(cid);
        controls.autoRotate = false;
        showPopup(obj.userData);
    }
});

let acid = "";

function cardSet(cid){
    const expo = exhibitors.find(item => item.cid == cid);
     if(expo){
        let logoimage = '../logo/' + cid + '.' + expo.logo;
        let img = '../expo/<?=$id?>/' + cid + '.jpg';
        $('#logo img').attr('src',logoimage);
        $('#image').attr('src',img);
        $('#title').text(expo.title);
        $('#subtitle').text(expo.subtitle);
        $('#description p').text(expo.description);
        $('#company,#companyname').text(expo.company);
        $('#url').attr('href',expo.url);
        $('#zip span').text(expo.zip);
        $('#address').text(expo.address1 + ", " + expo.address2 + ", " + expo.prefecture);
        $('#telno span').text(expo.telno);
        $('#booth').addClass('active');
        acid = cid;
        sendExhibitorLog(cid);
     }    
}

function sendExhibitorLog(cid) {
    $.post('./click.php', { exid: cid }).fail(function () {});
}

$('#url').on('click',function(){
    $.post('./access.php', { exid: acid }).fail(function () {});
})

$('#booth .close').on('click',function(){
    $('#booth').removeClass('active');
})

/* FSAP (アニメーション用簡易プロトコル) */
const fsap = {
    to: (target, vars) => {
        for (let key in vars) {
            if (key === 'duration' || key === 'ease') continue;
            target[`_f_${key}`] = vars[key];
        }
    }
};

renderer.setAnimationLoop(tick);
const clock = new THREE.Clock();

function tick() {
    if (currentDOMTarget?.closest('#ev,#categories')) { return; }

    raycaster.setFromCamera(mouse, camera);
    const intersects = raycaster.intersectObjects(panelMeshes, true);
    
    if (intersects.length > 0) {
        const obj = intersects[0].object;
        fsap.to(obj.scale, { x: 1.1, y: 1.1, z: 1.1 });
        fsap.to(obj.material, { opacity: 1.0 });
    } else {
        panelMeshes.forEach(mesh => {
            fsap.to(mesh.scale, { x: 1.0, y: 1.0, z: 1.0 });
            fsap.to(mesh.material, { opacity: 0.95 });
        });
    }

    panelMeshes.forEach(mesh => {
        const f_speed = 0.1;
        if (mesh.scale._f_x !== undefined) {
            mesh.scale.x += (mesh.scale._f_x - mesh.scale.x) * f_speed;
            mesh.scale.y += (mesh.scale._f_y - mesh.scale.y) * f_speed;
            mesh.scale.z += (mesh.scale._f_z - mesh.scale.z) * f_speed;
        }
        if (mesh.material._f_opacity !== undefined) {
            mesh.material.opacity += (mesh.material._f_opacity - mesh.material.opacity) * f_speed;
        }
    });

    renderer.render(scene, camera);
}

let currentDOMTarget = null;
window.addEventListener('mousemove', (event) => {
    currentDOMTarget = event.target;
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
});

function showPopup(data) {
    // ポップアップ詳細処理（必要に応じて）
}

function animate() {
    requestAnimationFrame(animate);
    bana.rotation.y += (angleToCamera - bana.rotation.y) * 0.02;
    if (bana.material.opacity < 1.0) {
        bana.material.opacity += (1.0 - bana.material.opacity) * 0.01;
    }
    controls.target.y = camera.position.y;
    camera.position.y = Math.max(camera.position.y, 0);
    controls.update();
    renderer.render(scene, camera);
}
animate();

}) // jquery
</script>
</body>
</html>