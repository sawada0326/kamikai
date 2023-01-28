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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" type="image/png" href="../image/favicon.png">
    <title>神回DB|<?php echo $category ?>版</title>
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
        <h1>神回DB<span><?php echo $category ?>版</span></h1>
        <div class="all_search_box">
            <form action="search.php?category=<?php echo $category ?>" method="GET" >
                <input type="hidden" name="category" value="<?php echo $category ?>">
                <input id="all_search" type="text" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="all_sub" value="検索">
            </form>
        </div>
            <div class="update_list">
                <h2><span></span>最新更新 神回</h2>
                <table>
                    <?php foreach($articles as $articleData): ?>
                        <tr data-href="detail.php?id=<?php echo h($articleData['id']); ?>">
                            <td>
                                <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass($articleData['category']) ?>"><?php echo h($articleData['category']) ?></p></td>
                            <td><?php echo h($articleData['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount($articleData['id']) ?>
                            <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count'] ?></td>
                        </tr>
                    <?php endforeach ?>                      
                </table>
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
    <script src="../javascript/table_click.js"></script>
    <script src="../javascript/menu_button.js"></script>
</body>
</html>