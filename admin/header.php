<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('auth.php');
?>
<header>
	<div class="inner">

	</div>
</header>

<nav>
	<div class="inner">
	<ul>
		<li><a href="index.php">トップ</a></li>		
		<li><a href="expo.php">展示会管理</a></li>		
		<li><a href="organizer.php">主催者管理</a></li>	
		<li><a href="company.php">出展社管理</a></li>
		<li><a href="logout.php">ログアウト</a></li>		
	</ul>
	</div>
</nav>