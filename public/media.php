<?php
    require_once('../classes/article.php');
    require_once('../lib/functions.php');

    $category = $_GET['category'];

    $article = new Article();
    $articles = $article->getCategoryAll($category);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="../javascript/font.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <title>神回図鑑|<?php echo $category ?>版</title>
</head>
<body>
    <header>
        <ul class="headerUL">
            <li><a href="all.php">総合</a></li>
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="chat.php?category=<?php echo $category ?>">掲示板</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header> 
    <div class="container">
        <h1>神回図鑑<span><?php echo $category ?>版</span></h1>
        <div class="all_search_box">
            <form action="search.php?category=<?php echo $category ?>" method="GET" >
                <input type="hidden" name="category" value="<?php echo $category ?>">
                <input id="all_search" type="text" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="all_sub" value="検索">
            </form>
        </div>
        <div class="update_list">
            <h2 class="home_h2">最新更新 神回</h2>
            <div class="cards_wrapper">
            <div class="cards">
                <?php $n = 0;?>
                <?php foreach($articles as $articleData): ?>
                    <?php if($n >= 27)break; ?>
                    <a class="card" href="detail.php?id=<?php echo h($articleData['id']); ?>">
                        <?php $img = $article->getImage($articleData['title']); ?>
                        <?php if(is_array($img) && empty($img)): ?>
                            <img src="../images/no_image.jpg" alt="">
                        <?php else: ?>
                            <?php if (file_exists($img[0]['path'])): ?>
                                <img src='<?php echo $img[0]['path'] ?>' alt="">
                            <?php else:?>
                                <img src="../images/no_image.jpg" alt="">
                            <?php endif ?>
                        <?php endif ?>
                        <p class="cate_mark <?php echo $article->setClass(h($articleData['category'])) ?>"><img src="../images/category_mark/<?php echo $article->setClass(h($articleData['category'])) ?>.png" alt=""></p>
                        <p class="star">
                            <?php for($i=1; $i <= h($articleData['rating']) ;$i++): ?>
                                <i class="fa fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </p>
                        <p class="title"><?php echo h($articleData['title']) ?></p>
                        <p class="onair"><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></p>
                        <?php $comment_count = $article->getCommentCount(h($articleData['id'])) ?>
                        <p class="comment_mark"><i class="fa fa-comment"></i> <?php echo h($comment_count[0]['count']) ?></p>
                    </a>
                    <?php $n++; ?>
                <?php endforeach ?>
            </div>
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
                <li><a href="chat.php?category=<?php echo $category ?>">掲示板</a></li>
                <li><a href="request_form.php">リクエスト</a></li>
                <li><a href="login_form.php">管理</li>
            </ul>
        </div>
        <p>@kamikai_db_administer</p>
    </footer>
    <script src="../javascript/scroll.js"></script>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>