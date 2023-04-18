<?php
    session_start();
        require_once('../classes/article.php');
        require_once('../classes/comment.php');
        require_once('../lib/functions.php');

        if(empty($_GET['category'])) {
            exit ('categoryがありません。');
        }

        $article = new Article();
        $category = $_GET['category'];

        $chat_id = $article->setArticleId($category);
        $comments = $article->getComments($chat_id);

        //コメントの投稿
        if (!empty($_POST) && empty($_SESSION['input_data'])) {
            //バリデーション
            $comment = new Comment();
            $comment->commentValidate($_POST);
        } elseif (!empty($_SESSION['input_data'])) {
            $_POST = $_SESSION['input_data'];
        }

        //バリデーションメッセージ
        $err = isset($_SESSION['e']) ? $_SESSION['e'] : null;
        unset($_SESSION['e']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <script src="../javascript/font.js"></script>
    <link rel="stylesheet" href="../css/chat.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <title>神回図鑑|<?php echo $category ?>版掲示板</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回図鑑</a></h1>
        <div class="search_box">
            <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索"　name="search"><input type="submit" id="sub_but" value="検索">
        </div>
        <ul class="headerUL">
            <li><a href="all.php">総合</a></li>
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>  
    <div class="chat">
        <div class="chat_title">
            <h2><span></span>神回情報｜<?php echo $category ?>版 掲示板</h2>
        </div>
        <div class="comments">
        <?php $i = 0; ?>
            <?php foreach($comments as $comment): ?>
                <div class ="comment">
                    <p><span><?php echo $i ?>.</span><?php echo h($comment['name']) ?></p>
                    <p><?php echo h($comment['text']) ?></p>   
                    <p><?php echo h($comment['post_at']) ?></p>     
                </div>
                <?php $i++; ?>
            <?php endforeach ?>      
        </div>
        <div class="text_box" id="box">
            <form method="POST">
                <input type="hidden" name="article_id" value="<?php echo $chat_id ?>">
                <input type="text" name="name" id="__name" placeholder="名前(省略可)" value="<?php if(!empty($_POST['name']))echo h($_POST['name']); ?>">
                <span><textarea name="text" id="__content" placeholder="本文を入力" cols="60" rows="5"></textarea><input id="__submit" type="submit" value="送信"></span>
                <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
            </form>
            <?php if(isset($err['text'])): ?>
                        <p><?php echo $err['text']; ?></p>
            <?php endif ?>
        </div>
    </div>
    <footer>
        <div class="footer_list">
            <ul>
                <li><a href="all.php">総合</a></li>
                <li><a href="media.php?category=テレビ">テレビ版</a></li>
                <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
                <li><a href="media.php?category=アニメ">アニメ版</a></li>
                <li><a href="request_form.php">リクエスト</a></li>
                <li><a href="login_form.php">管理</a></li>
            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/table_click.js"></script>
    <script src="../javascript/menu_button.js"></script>
    </body>
</html>