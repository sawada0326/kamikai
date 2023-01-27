<?php 
session_start();
require_once ('../classes/UserLogic.php');
require_once('../classes/article.php');
require_once('../lib/functions.php');

$result = UserLogic::checkLogin();

$err = [];

if(!$result) {
    //二重送信防止,ワンタイムトークン
    $token = filter_input(INPUT_POST, 'csrf_token');
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエストです');
    }
    unset($_SESSION['csrf_token']);

    if(!$login_id = filter_input(INPUT_POST, 'login_id')) {
        $err['id_err'] = 'ログインIDを記入してください';
    }
    if(!$password = filter_input(INPUT_POST, 'password')) {
        $err['pwd_err'] = 'パスワードを記入してください';
    }
    
    if(count($err) > 0) {
        //エラーがあった場合は戻す
        $_SESSION = $err;
        $_SESSION += $_POST;
        header('Location: ../public/login_form.php');
        return;
    }

    $input_data = new UserLogic();
    $result = $input_data->login($login_id, $password);
    
    if(!$result) {
        $_SESSION += $_POST;
        header('Location: ../public/login_form.php');
        return;
    }
}

$article = new Article();
$articles = $article->getAllAdmin();

$file_err = isset($_SESSION['file_err']) ? $_SESSION['file_err'] : null;
unset($_SESSION['file_err']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>神回DB|管理版</title>
</head>
<body>
    <?php if(count($err) > 0): ?>
        <?php foreach($err as $e): ?>
            <p><?php echo $e ?></p>
        <?php endforeach; ?>
    <?php else: ?>
    <?php if(isset($file_err)): ?>
        <p><?php echo $file_err ?></p>
    <?php endif; ?>
    <header>
        <ul class="headerUL">
            <li><a href="request.php">リクエストポスト</a></li>
            <li><a href="form.php">投稿フォーム</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header> 
    <div class="container">
        <h1>神回DB<span>-管理版-</span></h1>
        <div class="all_search_box">
            <form action="search.php" method="GET" >
                <input id="all_search" type="text" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="all_sub" value="検索">
            </form>
        </div>
            <div class="update_list">
                <h2><span></span>最新更新 神回</h2>
                <table>
                    <?php foreach($articles as $articleData): ?>
                    <tr data-href="detail.php?id=<?php echo h($articleData['id']); ?>" class="<?php if($articleData['publish_status'] === "0")echo 'private' ?>">
                        <td align="center">
                            <?php if($articleData['publish_status'] === "0"): ?>
                                <span><i class="fa fa-solid fa-lock lock"></i></span>
                            <?php endif; ?>
                            <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                                <i class="fa fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </td>
                        <td><p class="cate_mark <?php echo $article->setClass(h($articleData['category'])) ?>"><?php echo h($articleData['category']) ?></p></td>
                        <td><?php echo h($articleData['title']) ?></td>
                        <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                        <?php $comment_count = $article->getCommentCount(h($articleData['id'])) ?>
                        <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count'] ?></td>
                        <td><a href="./update_form.php?id=<?php echo h($articleData['id']); ?>">編集</a></td>
                        <td><a href="../lib/article_delete.php?id=<?php echo h($articleData['id']); ?>">削除</a></td>
                    </tr>
                    <?php endforeach ?>                      
                </table>
            </div>
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
        <script src="../javascript/table_click.js"></script>
        <script src="../javascript/menu_button.js"></script>
    </body>
    </html>
    <?php endif ?>