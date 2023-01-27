<?php
session_start();
require_once ('../classes/article.php');
require_once ('../classes/UserLogic.php');
require_once ('./functions.php');

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
$article->articleUpdate($articles, $file);

//ログインユーザ以外のセッションを削除
$save = $_SESSION['login_user'];
$save2 = $_SESSION['kk'];
$_SESSION = array();
$_SESSION['login_user'] = $save;
$_SESSION['kk'] = $save2;
$_SESSION['update'] = '変更が完了しました';
unset($_FILES);

$id = $articles['id'];
header("Location:../admin/detail.php?id={$id}");
?>
