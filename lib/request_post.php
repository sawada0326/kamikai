<?php
session_start();
require_once ('../classes/request.php');


if (!isset($_SESSION['input_data'])) {
    header('Location:../public/request_form.php');
    exit();
}

$requests = $_SESSION['input_data'];

$request = new Request();
$request->RequestPost($requests);

$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/post.css">
    <title>リクエスト完了</title>
</head>
<body>
    <div class="container">
        <main>
            <h3>リクエストありがとうございます！</h3>
            <a href="../public/request_form.php">戻る</a>
        </main>
    </div>
</body>
</html>