<?php
session_start();
require_once ('../classes/UserLogic.php');

if(!$logout = filter_input(INPUT_POST, 'logout')) {
    exit('不正なリクエストです');
}

//ログアウトする
UserLogic::logout();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/post.css">
    <title>ログアウト完了</title>
</head>
<body>
    <div class="container">
        <main>
            <h3>ログアウトが完了しました</h3>
            <a href="../public/login_form.php">ログイン画面へ</a>
        </main>
    </div>
</body>
</html>