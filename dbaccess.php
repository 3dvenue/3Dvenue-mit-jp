<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL & ~E_WARNING);

// --- データベース接続 ---
$servername = "Server　Name";
$dbname = "Database Name";
$username = "User Name";
$password = "Password";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_errno) {
    error_log("DB Connection failed: " . $conn->connect_error);
    echo "Unable to connect to the database.";
    exit; 
}

$conn->query("SET sql_mode='NO_AUTO_VALUE_ON_ZERO'");

$flagFile = __DIR__ . '/setup.lock';
if (file_exists($flagFile)) {
    // echo "Setup is already locked.";
    return; 
}

$queries = [];

// 1. Category Table
$queries[] = "CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `jname` varchar(100) NOT NULL,
  `vid` int(1) NOT NULL COMMENT 'venue_id',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Company Table
$queries[] = "CREATE TABLE IF NOT EXISTS `company` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) NOT NULL,
  `company` varchar(100) NOT NULL COMMENT '会社名 Corporate Name',
  `name` varchar(100) NOT NULL COMMENT '担当者名 Contact Person Name',
  `email` varchar(200) NOT NULL COMMENT 'メールアドレス Email Address',
  `password` varchar(100) NOT NULL,
  `telno` varchar(15) NOT NULL COMMENT '連絡先電話番号 Phone Number',
  `zip` varchar(10) NOT NULL COMMENT '郵便番号 Zip Code',
  `prefecture` varchar(10) NOT NULL COMMENT '都道府県 Prefecture',
  `address1` varchar(200) NOT NULL COMMENT '住所１ Address line 1',
  `address2` varchar(200) NOT NULL COMMENT '住所２ Address line 2',
  `logo` varchar(10) NOT NULL COMMENT 'ロゴの拡張子 Logo file extension',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:Yes, -1:Ban, 0:Off',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Exhibitors Table
$queries[] = "CREATE TABLE IF NOT EXISTS `exhibitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT 'company_id',
  `vid` int(11) NOT NULL COMMENT 'venue_id',
  `title` varchar(255) NOT NULL COMMENT 'タイトル Title',
  `subtitle` varchar(255) NOT NULL COMMENT 'サブタイトル又は英語 Subtitle/English',
  `description` text NOT NULL COMMENT '詳しい説明 Description',
  `category` int(1) NOT NULL COMMENT 'カテゴリ Category',
  `url` varchar(200) DEFAULT NULL COMMENT 'ホームぺージ Official Website',
  `telno` varchar(50) DEFAULT NULL COMMENT '連絡先電話番号 Contact Phone Number',
  `image` varchar(10) DEFAULT NULL,
  `tax` int(1) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '作成日 Created Date',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT '修正日 Updated Date',
  `click` int(4) NOT NULL DEFAULT '0',
  `access` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 4. Information Table
$queries[] = "CREATE TABLE IF NOT EXISTS `infomation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '新規タイトル' COMMENT 'タイトル Title',
  `content` text NOT NULL COMMENT '本文 Content',
  `target` int(1) NOT NULL DEFAULT '0' COMMENT '0:全体/All 1:契約者/Exhibitors 2:主催者/Organizers',
  `public` int(1) NOT NULL DEFAULT '0' COMMENT '0:非公開/Private 1:公開/Public',
  `published_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 5. Organizer Table
$queries[] = "CREATE TABLE IF NOT EXISTS `organizer` (
  `oid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'organizer_id',
  `uuid` varchar(32) NOT NULL COMMENT 'UUID',
  `company` varchar(100) NOT NULL COMMENT '企業団体名 Organization Name',
  `oname` varchar(100) NOT NULL COMMENT '担当者名 Contact Person Name',
  `email` varchar(200) NOT NULL COMMENT 'メールアドレス Email Address',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'パスワード Password',
  `telno` varchar(15) NOT NULL COMMENT '連絡可能な電話番号 Phone Number',
  `zip` varchar(15) NOT NULL COMMENT '郵便番号 Zip Code',
  `prefecture` varchar(10) NOT NULL COMMENT '都道府県 Prefecture',
  `address1` varchar(200) NOT NULL COMMENT '住所１ Address line 1',
  `address2` varchar(200) NOT NULL COMMENT '住所２ Address line 2',
  `create_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '申込日 Applied Date',
  `update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新日 Updated Date',
  `terms_agree` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1:Yes, -1:Ban, 0:Off',
  `reviewed_at` datetime NOT NULL,
  `reject_reason` text NOT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 6. Venue Table
$queries[] = "CREATE TABLE IF NOT EXISTS `venue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '展示会のタイトル Exhibition Title',
  `subtitle` varchar(100) NOT NULL,
  `organizers` varchar(200) NOT NULL DEFAULT '3DVeneu',
  `description` varchar(500) NOT NULL COMMENT '展示会の説明文 Exhibition Description',
  `benefit` varchar(500) NOT NULL DEFAULT 'Sample Benefit' COMMENT '展示会のメリット Benefits',
  `category` varchar(200) NOT NULL DEFAULT '0' COMMENT 'カテゴリ Category',
  `price` int(4) NOT NULL DEFAULT '10000',
  `start` datetime NOT NULL COMMENT '開始日 Start Date',
  `end` datetime NOT NULL COMMENT '終了日 End Date',
  `created_at` datetime NOT NULL COMMENT '作成日 Created Date',
  `venue_category` int(11) NOT NULL DEFAULT '0' COMMENT '展示会カテゴリ Venue Category',
  `background` varchar(100) NOT NULL COMMENT '背景画像 Background Image',
  `maincolor` varchar(30) NOT NULL DEFAULT '#FFFFFF' COMMENT 'main背景色 Main Background Color',
  `ptext` varchar(30) NOT NULL DEFAULT '#333333' COMMENT '段落テキスト Paragraph Text Color',
  `h3text` varchar(30) NOT NULL DEFAULT '#FFFFFF' COMMENT 'h3テキスト H3 Heading Color',
  `venuecolor` varchar(30) NOT NULL DEFAULT '#333333' COMMENT 'ぶち抜き背景 Venue Backdrop Color',
  `headercolor` varchar(30) NOT NULL DEFAULT '#333333' COMMENT 'ヘッダー背景 Header Background Color',
  `headertext` varchar(30) NOT NULL DEFAULT '#FFFFFF' COMMENT 'ヘッダー文字色 Header Text Color',
  `h2color` varchar(30) NOT NULL DEFAULT '#000000' COMMENT 'h2背景色 H2 Background Color',
  `h2text` varchar(30) NOT NULL DEFAULT '#FFFFFF' COMMENT 'h2テキスト色 H2 Text Color',
  `organizer` int(11) NOT NULL DEFAULT '0' COMMENT 'Organizer_id',
  `public` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0非公開:1公開 0:Private/1:Public',
  `other1` text DEFAULT NULL COMMENT '自由帳1 / Custom Config1',
  `other2` text DEFAULT NULL COMMENT '自由帳2 / Custom Config2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 7. View
$queries[] = "CREATE OR REPLACE VIEW `category_summary` AS 
SELECT `category`.`category_id` AS `category_id`, `category`.`name` AS `name`, `category`.`vid` AS `vid`, count(`exhibitors`.`category`) AS `cnt` FROM (`category` left join `exhibitors` on((`category`.`category_id` = `exhibitors`.`category`))) GROUP BY `category`.`category_id` ;";



// Organizer (3Dvenue / Cyber City)
$queries[] = "INSERT IGNORE INTO `organizer` (oid, uuid, company, oname, email, password, telno, zip, prefecture, address1, address2, status) 
              VALUES (1, MD5('sample'), '3Dvenue', 'Admin', 'admin@example.com', 'password', '000-0000-0000', '000-0000', 'Tokyo', 'Cyber City', '0-0-0', 0);";

// company (DDD Inc. / Geo City)
$queries[] = "INSERT IGNORE INTO `company` (cid, uuid, company, name, email, password, telno, zip, prefecture, address1, address2, status) 
              VALUES (1, MD5('sample_exh'), 'Digital Dream Deliver Inc.', 'DDD Master', 'ddd@example.com', 'password', '000-0000-0000', '000-0000', 'Tokyo', 'Geo City', '0-0-0', 0);";

// Venue (ZERO Square Japan)
$queries[] = "INSERT IGNORE INTO `venue` (id, name, subtitle, organizers, description, start, end, created_at, organizer, public) 
              VALUES (1, '次世代テクノロジーEXPO 2026', '未来を創る、空間コンピューティングの夜明け', '3DVenue 運営事務局', '最先端の3D技術とXRデバイスが集結する、国内最大級のバーチャル展示会です。', NOW(), NOW() + INTERVAL 6 DAY, NOW(), 1, 0);";

foreach ($queries as $sql) {
    if (!$conn->query($sql)) {
        die("SQL Error: " . $conn->error . "<br>Query: " . $sql);
    }
}

// setup.lock faile
file_put_contents($flagFile, date('Y-m-d H:i:s'));

exit("無事にデータベースが完了しました! 再読み込みをしてね。");
?>
