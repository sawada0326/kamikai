<?php
    session_start();
    require_once ('../classes/UserLogic.php');
    require_once('../classes/article.php');
    require_once ('./functions.php');


    $result = UserLogic::checkLogin();

    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
  
    $article = new Article();
    $program = $article->getById($_GET['id']);
    $sameTitleArticles = $article->getBySameTitle($program['title'], $_GET['id']);
    $image = $article->getImage($program['title']);

    $article->delete($_GET['id']);
    $article->commentsDelete($_GET['id']);

    //もしarticlesで番組が唯一無二なら画像を削除
    if(count($sameTitleArticles) == 0 && !empty($image)) {
        $article->imageDelete($program['title']);
        if(!unlink($image[0]['path'])) {
            $_SESSION['file_err'] = 'ファイルの削除には失敗しました';
        }
    }

    header('Location: ../admin/all.php');
?>
