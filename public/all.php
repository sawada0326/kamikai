<?php
    require_once('../classes/article.php');
    require_once('../lib/functions.php');


    $article = new Article();
    $articles = $article->getAll();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
    <link rel=”icon” type=”image/png” href=“../image/favicon.png”>
    <link rel="stylesheet" href="../css/style.css">
    <title>神回DB</title>
</head>
<body>
    <header>
        <ul class="headerUL">
            <li><a href="media.php?category=テレビ">テレビ版</a></li>
            <li><a href="media.php?category=ラジオ">ラジオ版</a></li>
            <li><a href="media.php?category=アニメ">アニメ版</a></li>
            <li><a href="request_form.php">リクエスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header> 
    <div class="container">
        <h1>神回DB</h1>
        <div class="all_search_box">
            <form action="search.php" method="GET" >
                <input id="all_search" type="text" placeholder="番組名・出演者名を検索" name="q"><input type="submit" id="all_sub" value="検索">
            </form>
        </div>
            <div class="update_list">
                <h2><span></span>最新更新 神回</h2>
                <table>
                    <?php foreach($articles as $articleData): ?>
                        <tr data-href="detail.php?id=<?php echo h($articleData['id']); ?>">
                            <td align="center">
                                <?php for($i=1; $i <= h($articleData['rating']) ;$i++): ?>
                                    <i class="fa fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </td>
                            <td><p class="cate_mark <?php echo $article->setClass(h($articleData['category'])) ?>"><?php echo h($articleData['category']) ?></p></td>
                            <td><?php echo h($articleData['title']) ?></td>
                            <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                            <?php $comment_count = $article->getCommentCount(h($articleData['id'])) ?>
                            <td><i class="fa fa-comment"></i> <?php echo h($comment_count[0]['count']) ?></td>
                        </tr>
                    <?php endforeach ?>                      
                </table>
            </div>
    </div>
    <footer>
        <div class="footer_list">
            <ul>
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