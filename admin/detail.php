<?php
    session_start();
    require_once ('../classes/UserLogic.php');
    require_once('../classes/article.php');
    require_once('../classes/comment.php');
    require_once('../lib/functions.php');


    $result = UserLogic::checkLogin();
    
    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
    $article = new Article();
    $articles = $article->getAllAdmin();
    $articleData = $article->getByIdAdmin($_GET['id']);
    $img = $article->getImage($articleData['title']);
    $tags = explode(",",$articleData['tags']);
    $sameTitleArticles = $article->getBySameTitle($articleData['title'], $articleData['id']);
    $comments = $article->getComments($articleData['id']);
    $filename = isset($img[0]['path']) ? $img[0]['path'] : null;

    $update = isset($_SESSION['update']) ? $_SESSION['update'] : null;
    unset($_SESSION['update']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/detail.css">
    <link rel=”icon” type=”image/png” href=“../image/favicon.png”>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <title>神回DB｜『<?php echo h($articleData['title']) ?>』<?php echo h($articleData['caption']) ?>の回</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB<span>-管理版-</span></a></h1>
        <form action="search.php" method="GET">
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
            <li><a href="request.php">リクエストポスト</a></li>
            <li><a href="request.php">投稿フォーム</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
    <div class="main">
        <?php if(!empty($update)): ?>
            <p><?php echo $update; ?></p>
        <?php endif ?>
        <div class="edit_div">
            <a href="./update_form.php?id=<?php echo h($articleData['id']); ?>">編集</a>
            <a href="../lib/article_delete.php?id=<?php echo h($articleData['id']); ?>">削除</a>
        </div>
        <div class="tag">
                <p>タグ：</p>
                <?php foreach($tags as $tag): ?>
                    <a href="search.php?tag=<?php echo h($tag) ?>"><?php echo $article->setTagName(h($tag)) ?></a>
                <?php endforeach ?>   
         </div>
         <h1><span></span><?php echo h($articleData['title']) ?></h1>
         <h2><span></span><?php echo h($articleData['caption']) ?></h2>
         <?php if(is_array($img) && empty($img)): ?>
                <img src="../images/no_image.jpg" alt="">
            <?php else: ?>
                <?php if (file_exists($img[0]['path'])): ?>
                    <img src='<?php echo $img[0]['path'] ?>' alt="">
                <?php else:?>
                    <img src="../images/no_image.jpg" alt="">
                <?php endif ?>
            <?php endif ?>
            <table>
                <tr>
                    <td>放送局</td>
                    <td><?php echo h($articleData['broadcaster']) ?></td>
                </tr>
                <tr>
                    <td>放送日</td>
                    <td class="date_time"><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                </tr>
                <tr>
                    <td>放送日時</td>
                    <td><?php echo date('H:i', strtotime($articleData['start_time'])) ?>~<?php echo date('H:i', strtotime($articleData['finish_time'])) ?></td>
                </tr>
                <tr>
                    <td>出演者</td>
                    <td>
                        <?php if(empty($articleData['casts'])): ?>
                            <span>出演者情報はまだありません</span>
                        <?php else: ?>
                            <span><?php echo h($articleData['casts']) ?> ほか</span>
                        <?php endif ?>
                    </td>
                </tr>
                <tr>
                    <td>神回度</td>
                    <td id ="star">
                        <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                            <i class="fa fa-solid fa-star"></i>
                        <?php endfor; ?>
                    </td>
                </tr>
                <tr>
                    <td>選考理由</td>
                    <td><?php echo h($articleData['reason']) ?></td>
                </tr>
            </table>
        </div>
        <div class="update_list_wrapper">
            <div class="update_list">
                <h2><span></span>『<?php echo h($articleData['title']) ?>』ほか神回</h2>
                <?php if(empty($sameTitleArticles)): ?>
                <p><span></span>同じタイトルの記事はありません</p>
                <?php else: ?>
                <table>
                    <?php foreach($sameTitleArticles as $sameTitleArticle): ?>
                        <tr data-href="detail.php?id=<?php echo h($sameTitleArticle['id']); ?>" class="<?php if($sameTitleArticle['publish_status'] === "0")echo 'private' ?>">
                            <td align="center">
                                <?php if($sameTitleArticle['publish_status'] === "0"): ?>
                                    <i class="fa fa-solid fa-lock lock"></i>
                                <?php endif; ?>
                                <?php for($i=1; $i <= $sameTitleArticle['rating'];$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass(h($sameTitleArticle['category']))?>"><?php echo $sameTitleArticle['category'] ?></p></td>
                            <td><?php echo h($sameTitleArticle['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($sameTitleArticle['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount(h($sameTitleArticle['id'])) ?>
                            <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count']?></td>
                            <td><a href="./update_form.php?id=<?php echo h($articleData['id']); ?>">編集</a></td>
                        <td><a href="../lib/article_delete.php?id=<?php echo h($articleData['id']); ?>">削除</a></td>
                        </tr>
                    <?php endforeach ?>                      
                </table>
                <?php endif ?>
            </div>
        </div>
        <div class="chat">
            <h2><span></span>コメント<small><?php echo count($comments) ?>件</small></h2>
            <div class="comments" id="box">
                <?php $i = 0; ?>
                <?php foreach($comments as $comment): ?>
                    <div class ="comment">
                        <p><span><?php echo $i ?>.</span><?php echo h($comment['name']) ?></p>
                        <p><?php echo h($comment['text']) ?></p>   
                        <p><?php echo h($comment['post_at']) ?></p>     
                        <p><a href="../lib/comment_delete.php?id=<?php echo h($comment['id'])?>&article_id=<?php echo h($articleData['id']) ?>">削除</a></p>     
                    </div>
                    <?php $i++; ?>
                <?php endforeach ?>      
            </div>
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
