<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">
<link rel="icon" href="/favicon.ico">
<link rel="stylesheet" type="text/css" href="../common/css/base.css">
<title>
主催者
</title>
<style type="text/css">
body,html{
    padding:0;
    margin:0;
    width:100%;
    height:100%;
    box-sizing: border-box;
}

body *{
    padding:0;
    margin:0;
    box-sizing: border-box;
}

.inner{
    margin:0 auto;
    width:100%;
    max-width:680px;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

main{
    height:calc(100% - 40px);
}

h1{
    text-align: center;
    margin-bottom:50px;
}

.error{
    text-align: center;
    margin-bottom:40px;
}

#form{
    max-width: 420px;
    border:1px solid #999;
    background:linear-gradient(#FFF,#EEE);
    padding:20px 40px;
    border-radius: 10px;
    margin: 0 auto;
}

form label{
    display: flex;
    justify-content: space-between;
    padding:0 0 20px;
}

form label input{
    padding:5px 10px;
    border:1px solid #999;
    border-radius: 5px;
}

#submitButton{
    text-align: right;
}

#message{
    text-align: center;
    font-weight:700;
    font-size:12px;
    color:red;
}

button{
    padding:5px 20px;
    border-radius:5px;
    background:#FFF;
    border:1px solid #999;
    cursor: pointer;
}

</style>
</head>
<body>
    <main>
        <div class="inner">

            <section id="login">
                <div style="margin-bottom:20px">このページはFAKEページです実際にはログインできません(実運用の時はこれを消してね)<br/>
                ログインの初期ページはorganizer_login.phpです。名前を変えて運用してください<br />
                この初期ログインページは、adminから全て運用する場合は不要です。</div>
                <h1>主催者ログイン</h1>
                <div id="form">
                    <form method="POST">
                        <label><span>メールアドレス：</span><input type="email" name="email" value="" placeholder="メールアドレス" required></label>
                        <label><span>パスワード：</span><input type="password" name="password" value="" placeholder="パスワード" required></label>
                        <div id="submitButton"><button type="submit" class="btn" name="submit" value="login">ログイン</button></div>
                        </form>
                </section>

            </div>
        </main>
    </body>
</html>