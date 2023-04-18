<?php
    require_once('../classes/article.php');
    require_once('../lib/functions.php');
    
    $article = new Article();


    if(empty(array_filter($_GET))) {
        header("Location:all.php");
        exit();
    }
    if(!empty($_GET['q'])) {
        $search = $_GET['q'];
        $articles = $article->getSearch($search);
        if(!empty($_GET['category'])) {
            $category = $_GET['category'];
            $categoryArticles = $article->getCategorySearch($search ,$category);
        }
    }

    if(!empty($_GET['tag'])) {
        $search = $article->setTagName($_GET['tag']);
        $articles = $article->getTagSearch($_GET['tag']);
    }

    if(empty($search) || ctype_space($search)) {
        header('Location: ' . $_SERVER['HTTP_REFERER'] );
        exit;
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../javascript/font.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/search.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <title>神回図鑑|"<?php echo $search ?>"の検索結果</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回図鑑</a></h1>
        <div class="search_box">
            <form action="search.php" method="GET">
                <input id="sear_box" name="q" type="text" value="<?php if(!empty($_GET['q'])) echo $search  ?>" placeholder="番組名・出演者名を検索"><input type="submit" id="sub_but" value="検索">
            </form>
        </div>
        <ul class="headerUL">
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
        <h2><span></span>"<?php echo $search ?>"の検索結果</h2>
        <?php if(!empty($category)): ?>
            <div class="update_list">
                <h3><span></span><?php echo $category ?>版検索</h3>
                <div class="cards">
                    <?php foreach($categoryArticles as $articleData): ?>
                    <?php if($articleData['publish_status'] === "0")continue; ?>
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
                    <?php endforeach ?>                                        
                </div>
            </div> 
        <?php endif ?>
        <div class="update_list">
                <?php if(!empty($category)): ?>
                    <h3><span></span>総合検索</h3>
                <?php endif ?>
                <div class="cards">
                    <?php foreach($articles as $articleData): ?>
                    <?php if($articleData['publish_status'] === "0")continue; ?>
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
                    <?php endforeach ?>                                        
                </div>
            </div>  
    </div>
    <footer>
        <div class="footer_list">
            <ul>
                <li><a href="all.php">総合</a></li>
                <li><a href="tv.html">テレビ版</a></li>
                <li><a href="radio.php">ラジオ版</a></li>
                <li><a href="anime.php">アニメ版</a></li>
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