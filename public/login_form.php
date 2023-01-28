<?php
session_start();
require_once('../classes/UserLogic.php');
require_once('../lib/functions.php');

$result = UserLogic::checkLogin();
if($result) {
  header('Location: ../admin/all.php');
  return;
}

$msg_err = isset($_SESSION['msg']) ? $_SESSION['msg'] : null;
unset($_SESSION['msg']);
$id_err = isset($_SESSION['id_err']) ? $_SESSION['id_err'] : null;
unset($_SESSION['id_err']);
$pwd_err = isset($_SESSION['pwd_err']) ? $_SESSION['pwd_err'] : null;
unset($_SESSION['pwd_err']);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <title>ログイン画面</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB</a></h1>
        <form action="search.php" method="GET">
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
        <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header> 
    <main>
        <form id="login_form" class="form_class" action="../admin/all.php" method="POST">
            <h2>管理人ログイン</h2>
            <div class="form_div">
                <?php if(isset($msg_err)): ?>
                    <p><?php echo $msg_err; ?></p>
                <?php endif ?>
                <label>ログインID:</label>
                <input class="field_class" name="login_id" type="text" value="<?php if(!empty($_SESSION['login_id']))echo h($_SESSION['login_id']); ?>" autofocus>
                <?php if(isset($id_err)): ?>
                    <p><?php echo $id_err; ?></p>
                <?php endif ?>
                <label>パスワード:</label>
                <input id="pass" class="field_class" name="password" type="password">
                   <?php if(isset($pwd_err)): ?>
                        <p><?php echo $pwd_err; ?></p>
                    <?php endif ?>
                <input type="hidden" name="csrf_token" value="<?php echo h(setToken()) ?>">
                <button class="submit_class" type="submit">ログイン</button>
            </div>
        </form>
    </main>
    <footer>
        <div class="footer_list">
            <ul>
                <li><a href="all.php">総合</a></li>
                <li><a href="media.php?category=テレビ">テレビ版</a></li>
                <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
                <li><a href="media.php?category=アニメ">アニメ版</a></li>
                <li><a href="request_form.php">リクエスト</a></li>
            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>