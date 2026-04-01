<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

require_once('auth.php');
?>
<header>
    <div class="inner">
        <h1 id="head">3DVenue</h1>
        <div><?=$company?>　マイページ</div>
    </div>
</header>
<nav>
    <div class="inner">
		<ul>
			<li class="top"><a href="./">ホーム</a></li>
			<li class="acount"><a href="acount.php">アカウント</a></li>
			<li class="logout"><a href="logout.php">ログアウト</a></li>
		</ul>
	</div>
</nav>