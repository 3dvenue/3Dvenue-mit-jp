<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

require_once('auth.php');
require_once "../config.php";

$vid = $_SESSION['vid'] ?? 0;
$cid = $login_id;

$sql = "SELECT * FROM venue WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$submit = $_POST['submit'] ?? null;

	if($submit == "venue"){
		$vid = $_POST['vid'];
	 	$_SESSION['vid'] = $vid;
	}

	if($submit == "edit"){
		$vid = $_POST['vid'];
	 	$_SESSION['vid'] = $vid;
	    header("Location: exhibit.php");
	    exit;
	}

		$title = $_POST['title'] ?? null;
		$subtitle = $_POST['subtitle'] ?? null;
		$description = $_POST['description'] ?? null;
		$category = $_POST['category'] ?? 0;
		$url = $_POST['url'] ?? null;
		$telno = $_POST['telno'] ?? null;

	if($submit == "add"){

		$sql = "INSERT INTO exhibitors (cid,vid,title,subtitle,description,category,url,telno,tax) VALUES ('$cid','$vid','$title','$subtitle','$description','$category','$url','$telno','$tax')";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute();
	    header("Location: index.php");
	    exit;
	}

	if($submit == "update"){
		$sql = "UPDATE exhibitors SET title = '$title',subtitle = '$subtitle',description = '$description',category = '$category',url = '$url',telno = '$telno' WHERE cid = '$cid' AND vid = '$vid'";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute();
	    header("Location: index.php");
	    exit;
	}


$logoimage = "../logo/".$cid.'.png?t='.time();


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/exhibit.css">
    <link rel="icon" href="../favicon.ico">
    <title></title>
<style type="text/css"></style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
<?php
$mycard = "../expo/{$vid}/{$cid}.jpg";
$mypath = "";
// $path = __DIR__ . "/" . $mycard;
$path = $mycard;
if (file_exists($path)) {
	$mypath = "card";
}

$title ?? null;
$subtitle ?? null;
$description ?? null;
$category ?? null;
$url ?? null;
$telno ?? null;
$image ?? null;
$status = "";

$sql = "SELECT * FROM exhibitors WHERE cid = '$cid' AND vid = '$vid'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $title = $row['title'];
    $subtitle = $row['subtitle'];
    $description = $row['description'];
    $category = $row['category'];
    $url = $row['url'];
    $telno = $row['telno'];
    $image = $row['image'];
	$status = "update";
} 


?>
<ol id="status" class="<?=$mypath?> <?=$status?>">
	<li class="step1">カード作成</li>
	<li class="step2">プロフィール入力</li>
	<li class="step3">完了</li>
</ol>

<div class="inner">

<section id="acount">
<h2>出展者登録</h2>
<p class="infomation">
</p>

<?php
$logoimage = "";
$sql = "SELECT * FROM company WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$cid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $company = $row['company'];
    $name = $row['name'];
    if($telno === ""){
	    $telno = $row['telno'];
    }
    $zip = $row['zip'];
    $prefecture = $row['prefecture'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
    $logo = $row['logo'];
    if(!empty($logo)){
    	$logoimage = "../logo/{$login_id}.{$logo}";
    }
 } 
?>

<section id="venue-card" class="<?=$mypath?>">
	<h3>出展カード</h3>
<?php 
	if($mypath == "card"){
?> 
<img src="<?=$mycard?>?t=<?=time()?>" alt="出展カード" />
<?php } ?>
</section>

<section id="makecard" class="<?=$mypath?>">

<h4>出展カード作成</h4>

<p id="cardmemo">
<strong>このカードは展示会でのブース識別に使用されます。</strong>
<span>下のエリアをクリックしてカードを作成またはアップロードしてください。</span>
<span>対応形式：JPG、PNG</span>
</p>

<div id="card">
	<div id="mycard" style="background:linear-gradient(#fff,#ccc);">
		<div id="logoimage" style="width:300px;top:40px;left:160px;background-image:url(<?=$logoimage?>);"><span id="caption">LOGO</span></div>
		<div id="companyname" style="font-size:30px;text-align:center;top:380px;left:10px;"><?=$company?></div>
	</div>

	<div id="editor">
		<div id="cardclose">&times;</div>

		<div id="text">
			会社名：
			<span id="textshow"></span>
			<span id="textleft" class="align left"><img src="../img/align-left.svg"></span>
			<span id="textcenter"class="align center"><img src="../img/align-center.svg"></span>
			<span id="textright" class="align right"><img src="../img/align-right.svg"></span>
			<span id="bold"><img src="../img/bold.svg"></span>
			<span><input type="color" id="fontcolor" name="fontcolor"></span>
			<span><input type="number" id="fontsize" name="fontsize" max="50" min="14" step="0.1" value="30">px</span>
			<span id="textup" class="updown"><img src="../img/up.svg"></span>
			<span id="textdown" class="updown"><img src="../img/down.svg"></span>
		</div>

		<div id="background">
			背景：
			<input type="color" id="bgcolor1" name="bgcolor1" value="#FFFFFF">
			<input type="color" id="bgcolor2" name="bgcolor2" value="#CCCCCC">
			<span id="backgroundupload" class="upload"><img src="../img/cloud.svg"></span>
			<span id="trash"><img src="../img/trash.svg"></span>
		</div>

		<div id="logo">
			ロゴ：
			<span id="logoshow"></span>
			<input type="checkbox" id="show" name="show">
			<span id="big"><img src="../img/small.svg"></span>
			<span id="small"><img src="../img/big.svg"></span>
			<span id="logoleft"><img src="../img/left.svg"></span>
			<span id="logoright"><img src="../img/right.svg"></span>
			<span id="logoup"><img src="../img/up.svg"></span>
			<span id="logodown"><img src="../img/down.svg"></span>
			<!-- <span id="logoupload" class="upload"><img src="../img/cloud.svg"></span> -->
		</div>

		<div><button id="cardset" class="btn">カードプレビュー</button></div>
	</div>
</div>
</section>

<section id="infomation" class="<?=$mypath?>">
<div id="textform">

<h3>出展情報編集</h3>
<p class="note <?=$status?>">自社の強みが伝わるプロフィールを作成してください</p>
<form method="post">
	<table class="<?=$status?>">
		<tr>
			<th>キャッチコピー</th>
			<td><input type="text" name="title" value="<?=$title?>" required></td>
		</tr>
		<tr>
			<th>サブタイトル</th>
			<td><input type="text" name="subtitle" value="<?=$subtitle?>" required></td>
		</tr>
		<tr>
			<th>出展内容</th>
			<td><textarea name="description" required><?=$description?></textarea></td>
		</tr>
		<tr>
			<th>カテゴリ</th>
			<td>
				<select name="category" id="category">
					<option value="">選択してください</option>
					<?php
						$sql = "SELECT * FROM category WHERE vid = ?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param("i", $vid);
						$stmt->execute();
						$result = $stmt->get_result();
						while($row = $result->fetch_assoc()) {
							$selected = "";
							$c_id = $row['category_id'];
							$name = $row['name'];
							if($category == $c_id){
								$selected = "selected";
							}
					?>
					<option value="<?=$c_id?>" <?=$selected?>><?=$name?></option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>URL</th>
			<td><input type="url" name="url" value="<?=$url?>"></td>
		</tr>
		<tr>
			<th>電話番号</th>
			<td><input type="tel" name="telno" value="<?=$telno?>"></td>
		</tr>
		<tr>
			<td colspan="2">
				<button type="submit" name="submit" id="add" value="add">登録</button>
				<button type="submit" name="submit" id="update" value="update">更新</button>
			</td>
		</tr>
	</table>
</form>
</div>
</section>

</div>

<div id="view">
	<div id="closeview">&times;</div>
		<h3>カードプレビュー</h3>
		<div id="result"></div>
			<p>
				この画像は保存され、ブースに表示されます。<br>
				右クリックで保存できます。<br>
				今後の展示会でも再利用できます。<br>
				<span>※背景画像としてアップロードされます。</span>
			</p>
	<button id="dataupload">保存して次へ</button>
</div>

<script src="../common/js/jquery.js"></script>
<script src="../common/js/dom-to-image.min.js"></script>
<script type="text/javascript">
$(function(){
    let prefecture = "<?=$prefecture?>";
    $('#prefecture').val('<?=$prefecture?>');

    let timer;
    const wait = 50;
    $('#editor span').on('mousedown',function(){
        clearInterval(timer);
        let top = 0;
        let left = 0;
        let id = $(this).attr('id');

        switch (id) {

        case "trash":
            $('#mycard').css({'background-image':'none'});
            break;

        case "textleft":
            $('#companyname').css({'text-align':'left'});
            break;

        case "textcenter":
            $('#companyname').css({'text-align':'center'});
            break;

        case "textright":
            $('#companyname').css({'text-align':'right'});
            break;

        case "textup":
            top = parseInt($('#companyname').css('top'));
            timer = setInterval(function(){
                top--;
                $('#companyname').css({'top':top+'px'});
            },wait);
            break;

        case "textdown":
            top = parseInt($('#companyname').css('top'));
            timer = setInterval(function(){
                top++;
                $('#companyname').css({'top':top+'px'});
            },wait);
            break;

        case "big":
            size = parseInt($('#logoimage').css('width'));
            timer = setInterval(function(){
                size++;
                $('#logoimage').css({'width':size+'px'});
            },wait);
            break;

        case "small":
            size = parseInt($('#logoimage').css('width'));
            timer = setInterval(function(){
                size--;
                $('#logoimage').css({'width':size+'px'});
            },wait);
            break;

        case "logoleft":
            left = parseInt($('#logoimage').css('left'));
            timer = setInterval(function(){
                left--;
                $('#logoimage').css({'left':left+'px'});
            },wait);
            break;

        case "logoright":
            left = parseInt($('#logoimage').css('left'));
            timer = setInterval(function(){
                left++;
                $('#logoimage').css({'left':left+'px'});
            },wait);
            break;

        case "logoup":
            top = parseInt($('#logoimage').css('top'));
            timer = setInterval(function(){
                top--;
                $('#logoimage').css({'top':top+'px'});
            },wait);
            break;

        case "logodown":
            top = parseInt($('#logoimage').css('top'));
            timer = setInterval(function(){
                top++;
                $('#logoimage').css({'top':top+'px'});
            },wait);
            break;
        }
    });

    $(document).on('mouseup',function(){
        clearInterval(timer);
    });

    $('#mycard').on('click',function(){
    	$('#card').removeClass();
    	$('#card').addClass('background');
    	$('#imageupload').removeClass();
    	$('#imageupload').addClass('background');
    });

    $('#companyname').on('click',function(e){
    	let size = parseInt($(this).css('font-size'));
    	$('#fontsize').val(size);
    	e.stopPropagation();
    	$('#card').removeClass();
    	$('#card').addClass('text');
    });

    $('#logoimage').on('click',function(e){
		e.stopPropagation();
    	$('#card').removeClass();
    	$('#card').addClass('logo');
    	$('#imageupload').removeClass();
    	$('#imageupload').addClass('logo');
    });

    $('#fontsize').on('input',function(){
    	let size = $('#fontsize').val();
    	$('#companyname').css({'font-size':size+'px'});
    })

    $('#editor span').on('click',function(){
    	let id = $(this).attr('id');
		switch (id) {
		  case "bold": //Bold Type
		  	$('#companyname').toggleClass('bold');
		    break;

		  case "textshow": //Show 
		  	$('#companyname').toggleClass('hidden');
		    break;

		  case "logoshow": //Hidden
		  	$('#logoimage').toggleClass('hidden');
		    break;
		}

    });

    $('#editor input').on('input',function(){
    	let id = $(this).attr('id');
    	// console.log(id);
		switch (id) {
	
		  case "fontcolor": //Text Color
	    	let color = $(this).val();
		  	$('#companyname').css({'color':color});
		    break;

		  case "bgcolor1": //Top Color
		  case "bgcolor2": //Bottom Color
	    	let color1 = $('#bgcolor1').val();
	    	let color2 = $('#bgcolor2').val();
		  	$('#mycard').css({'background':'linear-gradient('+color1+','+color2+')'});
		    break;

		}

    })


	$('#imageupload').on('change', function () {
	  let name = $('#imageupload').attr('class');
		  console.log(name);

		  const file = this.files[0];
		  if (!file) return;
		  const reader = new FileReader();
		  reader.onload = function (e) {
		  	if(name == 'logo'){
			    $('#logoimage').css('background-image', `url(${e.target.result})`);
		  	}

		  	if(name == 'background'){
			    $('#mycard').css('background-image', `url(${e.target.result})`);
		  	}

		  };
		  reader.readAsDataURL(file);
	});

    $('#cardclose').on('click',function(){
    	$('#card').removeClass();
    })

    $('#cardset').on('click',function(){
    	MakeBana();
    })

    $('#closeview').on('click',function(){
    	$('#view').removeClass('view');
    })

 	// Create Bana
    function MakeBana(){
    	$('#card').removeClass();
    	$('#view').addClass('view');
        const node = $('#mycard').get(0);
        domtoimage.toPng(node)
        .then(function (dataUrl) {
            $('#result').empty().append('<img src="' + dataUrl + '">');
            // $('#eyecatch').removeClass('view');
        })
        .catch(function (error) {
            alert('Banner creation failed');
        });
    };

 	// Bana Save
	$('#dataupload').on('click', function () {
	  const base64 = $('#result img').attr('src');
	  $.post('uploadlogo.php', {
	    image: base64
	  }).done(function (res) {
	    alert('Sccess');
	    location.reload(true);
	  }).fail(function (xhr) {
	    alert('Erorr: ' + xhr.responseText);
	  });
	    $('#view').removeClass();
	});


	// Check card view
	function cardReset(){
		$('#mycard').css({'background-image': 'url("/expo/<?= $vid ?>/<?= $cid ?>.jpg")'});
		$('#logoimage,#companyname').addClass('hidden');
	}

	$('#venue-card img').on('click',function(){
		$('#venue-card,#makecard').removeClass('card');
	})

})

</script>
</body>
</html>
