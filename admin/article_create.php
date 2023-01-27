<?php
session_start();
require_once ('../classes/UserLogic.php');
require_once ('../classes/article.php');
require_once('../classes/request.php');
require_once ('../lib/functions.php');


//二重送信防止,ワンタイムトークン
$result = UserLogic::checkLogin();

if(!$result) {
    $_SESSION['msg'] = 'ログインしてください';
    header('Location: ../public/login_form.php');
    return;
}

if (!isset($_SESSION['input_data'])) {
    header('Location:../admin/form.php');
    exit();
}

$articles = $_SESSION['input_data'];
$file = isset($articles['img']) ? $articles['img'] : [];
$article = new Article();
$article->articleCreate($articles, $file);
//リクエストから挿入した場合
if(!empty($articles['request_id'])) {
    $request = new Request();
    $request->delete($articles['request_id']);
}

//ログインユーザ以外のセッションを削除
$save = $_SESSION['login_user'];
$_SESSION = array();
$_SESSION['login_user'] = $save;
unset($_FILES);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/post.css">
    <title>作成完了</title>
</head>
<body>
    <div class="container">
        <main>
            <h3>投稿しました!</h3>
            <div class="button_div">
                <a href="form.php">再び投稿する</a>
                <a href="all.php">ホームに戻る</a>
            </div>
        </main>
    </div>
</body>
</html>