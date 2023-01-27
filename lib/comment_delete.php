<?php
    session_start();
    require_once('../classes/comment.php');
    require_once('../classes/UserLogic.php');
    require_once('./functions.php');

    $result = UserLogic::checkLogin();

    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
  
    
    $comment = new Comment();
    $commentData = $comment->delete($_GET['id']);

    $article_id = $_GET['article_id'];
    header("Location: ../admin/detail.php?id={$article_id}#box");
?>