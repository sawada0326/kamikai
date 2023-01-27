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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/search.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel=”icon” type=”image/png” href=“../image/favicon.png”>
    <title>神回DB|"<?php echo $search ?>"の検索結果</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB</a></h1>
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
                    <table>
                        <?php foreach($categoryArticles as $articleData): ?>
                        <?php if($articleData['publish_status'] === "0")continue; ?>
                        <tr data-href="detail.php?id=<?php echo h($articleData['id']); ?>">
                            <td align="center">
                                <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass($articleData['category'])?>"><?php echo h($articleData['category']) ?></p></td>
                            <td><?php echo h($articleData['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount(h($articleData['id'])) ?>
                            <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($categoryArticles)): ?>
                            <p><span></span>検索結果はありません</p>
                        <?php endif ?>                  
                    </table>
            </div> 
        <?php endif ?>
        <div class="update_list">
                <?php if(!empty($category)): ?>
                    <h3><span></span>総合検索</h3>
                <?php endif ?>
                <table>
                    <?php $bool = false ?>
                    <?php foreach($articles as $articleData): ?>
                    <?php if($articleData['publish_status'] === "0")continue; ?>    
                    <?php if(!empty($category) && $articleData['category'] == $category)continue; ?>
                    <?php $bool = true; ?>
                    <tr data-href="detail.php?id=<?php echo h($articleData['id']); ?>">
                        <td>
                            <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                                <i class="fa fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </td>
                        <td><p class="cate_mark <?php echo $article->setClass($articleData['category'])?>"><?php echo $articleData['category'] ?></p></td>
                        <td><?php echo h($articleData['title']) ?></td>
                        <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                        <?php $comment_count = $article->getCommentCount($articleData['id']) ?>
                        <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count'] ?></td>
                    </tr>
                    <?php endforeach ?> 
                    <?php if(!$bool): ?>
                    <p><span></span>検索結果はありません</p>
                    <?php endif ?>                    
                </table>

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
    <script src="../javascript/table_click.js"></script>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>