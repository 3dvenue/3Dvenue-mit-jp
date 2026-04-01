<header>
	<div class="inner">
		<div id="logo"><a href="./"><img src="./img/logo.webp" alt="バーチャル空間展示会" /><p>3DVenue<span>バーチャル展示会</span></a></p></div>
		<div id="auth">
<!-- ボタン表示の条件分岐 -->
<?php if (isset($_SESSION['cid'])): ?>
<!--     <div><a href="dashboard">ダッシュボード</a></div>
    <div><a href="logout.php">ログアウト</a></div>
 --><?php else: ?>
<!--     <div><a href="login.php">ログイン</a></div>
    <div><a href="signup.php">新規登録</a></div>
 -->
<?php endif; ?>
		</div>
		<nav>
			<ul>
				<li><a href="./about.php">概要</a></li>
				<li><a href="./expo.php">展示会</a></li>
				<li><a href="./download.php">ダウンロード</a></li>
				<li><a href="./login.php">ログイン</a></li>
			</ul>
		</nav>
	</div>
</header>