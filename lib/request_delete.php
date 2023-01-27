<?php
    session_start();
    require_once('../classes/UserLogic.php');
    require_once('../classes/request.php');
    require_once('./functions.php');

    $result = UserLogic::checkLogin();

    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
  

    $request = new Request();
    $requestData = $request->delete($_GET['id']);

    header('Location: ../admin/request.php');
    return;
?>