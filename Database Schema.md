# Database Schema

## 1. category
| カラム | 型 | 説明 |
|------|----|------|
| category_id | int | PK |
| name | varchar(100) | カテゴリ名 |
| jname | varchar(100) | 日本語名 |
| vid | int | 会場ID |

---

## 2. company（出展企業）
| カラム | 型 | 説明 |
|------|----|------|
| cid | int | PK |
| uuid | varchar(32) | UUID |
| company | varchar(100) | 会社名 |
| name | varchar(100) | 担当者 |
| email | varchar(200) | メール |
| password | varchar(100) | パスワード |
| telno | varchar(15) | 電話 |
| zip | varchar(10) | 郵便番号 |
| prefecture | varchar(10) | 都道府県 |
| address1 | varchar(200) | 住所1 |
| address2 | varchar(200) | 住所2 |
| logo | varchar(10) | ロゴ拡張子 |
| status | tinyint | 状態 |

---

## 3. exhibitors（出展情報）
| カラム | 型 | 説明 |
|------|----|------|
| id | int | PK |
| cid | int | company_id |
| vid | int | venue_id |
| title | varchar(255) | タイトル |
| subtitle | varchar(255) | サブタイトル |
| description | text | 説明 |
| category | int | カテゴリ |
| url | varchar(200) | URL |
| image | varchar(10) | 画像 |
| click | int | クリック数 |
| access | int | アクセス数 |

---

## 4. infomation
| カラム | 型 | 説明 |
|------|----|------|
| id | int | PK |
| title | varchar(100) | タイトル |
| content | text | 内容 |
| target | int | 対象 |
| public | int | 公開状態 |

---

## 5. organizer（主催者）
| カラム | 型 | 説明 |
|------|----|------|
| oid | int | PK |
| uuid | varchar(32) | UUID |
| company | varchar(100) | 団体名 |
| oname | varchar(100) | 担当者 |
| email | varchar(200) | メール |
| password | varchar(100) | パスワード |
| status | tinyint | 状態 |

---

## 6. venue（展示会）
| カラム | 型 | 説明 |
|------|----|------|
| id | int | PK |
| name | varchar(100) | 展示会名 |
| subtitle | varchar(100) | サブタイトル |
| description | varchar(500) | 説明 |
| start | datetime | 開始日 |
| end | datetime | 終了日 |
| organizer | int | 主催者ID |
| public | tinyint | 公開状態 |

---

## VIEW

### category_summary
カテゴリごとの出展数を集計するVIEW