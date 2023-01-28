<?php
session_start();
require_once('../classes/UserLogic.php');
require_once('../lib/functions.php');

$result = UserLogic::checkLogin();
    
if(!$result) {
    $_SESSION['msg'] = 'ログインしてください';
    header('Location: ../public/login_form.php');
    return;
}

$_SESSION['e'] = array();

//POSTの値がある かつ sessionにインプットされているデータがあるか
if (!empty($_POST) && empty($_SESSION['input_data'])) {
    //二重送信防止,ワンタイムトークン
    $token = filter_input(INPUT_POST, 'csrf_token');
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }
    unset($_SESSION['csrf_token']);

    $login_id = $_SESSION['login_user']['login_id'];

    $input_data = new UserLogic();
    $input_data->PasswordChangeValidate($login_id, $_POST);

} elseif (!empty($_SESSION['input_data'])) {
    $_POST = $_SESSION['input_data'];
}

//メッセージ
$msg = isset($_SESSION['msg']) ? $_SESSION['msg'] : null;
unset($_SESSION['msg']);
$complete = isset($_SESSION['complete']) ? $_SESSION['complete'] : null;
unset($_SESSION['complete']);
$err = isset($_SESSION['e']) ? $_SESSION['e'] : null;
unset($_SESSION['e']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-U-ACompatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <title>パスワード変更画面</title>
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
            <li><a href="chat.php">掲示板</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header> 
    <main>
        <form id="login_form" class="form_class" method="POST">
            <?php if(isset($complete)): ?>
                <p id="complete"><?php echo $complete; ?></p>
            <?php elseif(isset($msg)): ?>
                <p><?php echo $msg; ?></p>
            <?php endif ?>
            <h2>パスワード変更画面</h2>
            <div class="form_div">
                <label>元のパスワード:</label>
                <input id="pass" class="field_class" name="old_password" type="password" value="<?php if(!empty($_POST['old_password']))echo h($_POST['old_password']); ?>" autofocus>
                <?php if(isset($err['old'])): ?>
                    <p><?php echo $err['old']; ?></p>
                <?php endif ?>
                <label>新規パスワード:</label>
                <input class="field_class" name="new_password" type="password" value="<?php if(!empty($_POST['new_password']))echo h($_POST['new_password']); ?>">
                <?php if(isset($err['new'])): ?>
                    <p><?php echo $err['new']; ?></p>
                <?php endif ?>
                <label>新規パスワード確認:</label>
                <input class="field_class" name="new_password_conf" type="password" value="<?php if(!empty($_POST['new_password_conf']))echo h($_POST['new_password_conf']); ?>">
                   <?php if(isset($err['conf'])): ?>
                        <p><?php echo $err['conf']; ?></p>
                    <?php endif ?>
                <input type="hidden" name="csrf_token" value="<?php echo h(setToken()) ?>">
                <button class="submit_class" type="submit">パスワード変更</button>
            </div>
        </form>
    </main>
    <footer>
        <div class="footer_list">
            <ul>
                <li><a href="all.php">総合</a></li>
                <li><a href="request.php">リクエストポスト</a></li>
                <li><a href="form.php">投稿フォーム</a></li>
                <li><a href="change_password.php">パスワード変更</a></li>
                <li><form action="../admin/logout.php" name="form1" method="POST">
                    <input type="hidden" name="logout" value="ログアウト">
                    <a href="javascript:form1.submit()">ログアウト</a>
                </form>
                </li>

            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>