<?php
    session_start();
    require_once('../classes/UserLogic.php');
    require_once('../classes/article.php');
    require_once('../classes/request.php');
    require_once('../lib/functions.php');
    

    $result = UserLogic::checkLogin();
    
    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
    $login_user = $_SESSION['login_user'];

    $request = new request();
    $requests = $request->getAllAdmin();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8" />
<title>神回DB｜投稿フォーム</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="stylesheet" href="../css/request_post.css">
</head>

<body>
    <header>
        <h1><a href="all.php">神回DB<span>-管理版-</span></a></h1>
        <form action="search.php" method="GET" >
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索"　name="search"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
            <li><a href="form.php">投稿フォーム</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
        <h2>神回リクエストフォーム</h2>
        <a href="form.php"><span></span>新規作成</a>
            <ul class="accordion-area">
                <?php foreach($requests as $request): ?>
                        <li>
                            <section>
                                <h3 class="title">『<?php echo h($request['title']) ?>』<?php echo date('Y年n月j日放送回', strtotime(h($request['on_air_date']))) ?></h3>
                                <div class="box">
                                <p><?php echo h($request['reason']) ?></p>
                                    <span>
                                        <a href="request_insert_form.php?id=<?php echo h($request['id']) ?>">編集</a>
                                        <a href="../lib/request_delete.php?id=<?php echo h($request['id']) ?>">削除</a>
                                    </span>
                                </div>
                            </section>
                        </li>
                <?php endforeach ?>    
            </ul>
    </div>
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
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="../javascript/table_click.js"></script>
    <script src="../javascript/menu_button.js"></script>
    <script src="../javascript/accordion.js"></script>
</body>

</html>