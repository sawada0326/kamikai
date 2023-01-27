<?php
session_start();
require_once('../lib/functions.php');
require_once('../classes/UserLogic.php');
require_once('../classes/request.php');

//POSTの値がある かつ sessionにインプットされているデータがあるか
if (!empty($_POST) && empty($_SESSION['input_data'])) {
    //二重送信防止
    $token = filter_input(INPUT_POST, 'csrf_token');
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }
    unset($_SESSION['csrf_token']);
    //バリデーション
    $request = new Request();
    $request->RequestValidate($_POST);

} elseif (!empty($_SESSION['input_data'])) {
    $_POST = $_SESSION['input_data'];
}

//バリデーションメッセージ
$err = isset($_SESSION['e']) ? $_SESSION['e'] : null;
unset($_SESSION['e']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/form.css">
    <link rel=”icon” type=”image/png” href=“../image/favicon.png”>
    <title>神回DB | リクエストフォーム</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB</a></h1>
        <form action="search.php" method="GET" >
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索"　name="search"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
            <li><a href="all.php">総合</a></li>
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>   
    <div class="container">
    <h2>神回リクエストフォーム</h2>
    <form method="POST">
        <div class="column_wrapper">
        <?php if(isset($err['title'])): ?>
            <p><?php echo $err['title']; ?></p>
        <?php endif ?>
            <div class="column">
                    <label>タイトル</label>
                    <input type="text" name="title" style="width:300px;" value="<?php if(!empty($_POST['title']))echo h($_POST['title']); ?>">
            </div>
            <div class="column">
                    <label>放送日</label>
                    <input type="date" name="on_air_date" value="<?php if(!empty($_POST['on_air_date']))echo h($_POST['on_air_date']); ?>">
            </div>
            <div id="content_table">
                <p>魅力を教えてください</p>
                <textarea name="reason" id="content" cols="70" rows="3"><?php if(!empty($_POST['reason']))echo h($_POST['reason']); ?></textarea>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo h(setToken()) ?>">
            <input type="submit" id="button" value="送信">
        </div>
    </form>
    </div>
    <footer>
        <div class="footer_list">
            <ul>
                <li><a href="all.php">総合</a></li>
                <li><a href="media.php?category=テレビ">テレビ版</a></li>
                <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
                <li><a href="media.php?category=アニメ">アニメ版</a></li>
                <li><a href="login_form.php">管理</li>
            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>