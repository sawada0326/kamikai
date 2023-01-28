<?php
    session_start();
    require_once('../classes/article.php');
    require_once('../classes/comment.php');
    require_once('../lib/functions.php');

    $article = new Article();
    $articles = $article->getAll();
    $articleData = $article->getById($_GET['id']);
    $tags = explode(",",$articleData['tags']);
    $img = $article->getImage($articleData['title']);
    $sameTitleArticles = $article->getBySameTitle($articleData['title'], $articleData['id']);
    $comments = $article->getComments($articleData['id']);

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
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/detail.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <title>神回DB｜『<?php echo h($articleData['title']) ?>』<?php echo h($articleData['caption']) ?>の回</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB</a></h1>
        <form action="search.php" method="GET">
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
    <div class="main">
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
                <tr class="date_time">
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
                        <?php if($sameTitleArticle['publish_status'] === 0)continue; ?>
                        <tr data-href="detail.php?id=<?php echo h($sameTitleArticle['id']); ?>">
                            <td align="center">
                                <?php for($i=1; $i <= $sameTitleArticle['rating'];$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass(h($sameTitleArticle['category']))?>"><?php echo $sameTitleArticle['category'] ?></p></td>
                            <td><?php echo h($sameTitleArticle['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($sameTitleArticle['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount(h($sameTitleArticle['id'])) ?>
                            <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count']?></td>
                        </tr>
                    <?php endforeach ?>                      
                </table>
                <?php endif ?>
            </div>
            <div class="update_list">
                <h2><span></span>おすすめ神回</h2>
                <table>
                    <?php $n = 0;?>
                    <?php shuffle($articles) ?>
                    <?php foreach($articles as $recommend): ?>
                    <?php if($n >= 4)break ?>
                    <?php if($recommend['title'] == $articleData['title'])continue ?>
                        <tr data-href="detail.php?id=<?php echo h($recommend['id']); ?>">
                            <td align="center">
                                <?php for($i=1; $i <= $recommend['rating'];$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass(h($recommend['category']))?>"><?php echo h($recommend['category']) ?></p></td>
                            <td><?php echo h($recommend['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($recommend['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount($recommend['id']) ?>
                            <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count']?></td>
                        </tr>
                    <?php $n++; ?>
                    <?php endforeach ?>                      
                </table>
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
                    </div>
                    <?php $i++; ?>
                <?php endforeach ?>      
            </div>
            <div class="text_box">
                <form method="POST">
                    <input type="hidden" name="article_id" value="<?php echo h($articleData['id']) ?>">
                    <input type="text" name="name" id="__name" placeholder="名前(省略可)" value="<?php if(!empty($_POST['name']))echo h($_POST['name']); ?>">
                    <span><textarea name="text" id="__content" placeholder="本文を入力" cols="60" rows="5"></textarea><input id="__submit" type="submit" value="送信"></span>
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                </form>
                <?php if(isset($err['text'])): ?>
                        <p><?php echo $err['text']; ?></p>
                <?php endif ?>
            </div>
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
                <li><a href="login_form.php">管理</li>
            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/table_click.js"></script>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>