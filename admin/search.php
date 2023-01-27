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
    $login_user = $_SESSION['login_user'];
    $article = new Article();

    if(empty($_GET)) {
        header("Location:all.php");
        exit();
    }

    if(!empty($_GET['q'])) {
        $search = $_GET['q'];
        $articles = $article->getSearch($search);
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
    <title>神回DB|"<?php echo $search ?>"の検索結果</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回DB<span>-管理版-</span></a></h1>
        <div class="search_box">
            <form action="search.php" method="GET">
                <input id="sear_box" name="q" type="text" value="<?php if(!empty($_GET['q'])) echo $search  ?>" placeholder="番組名・出演者名を検索"><input type="submit" id="sub_but" value="検索">
            </form>
        </div>
        <ul class="headerUL">
            <li><a href="form.php">投稿フォーム</a></li>
            <li><a href="request.php">リクエストポスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
        <h2><span></span>"<?php echo $search ?>"の検索結果</h2>
        <div class="update_list">
                <table>
                    <?php foreach($articles as $articleData): ?>
                    <?php if(!empty($category) && $articleData['category'] == $category)continue; ?>
                    <tr data-href="detail.php?id=<?php echo $articleData['id']; ?>" class="<?php if($articleData['publish_status'] === "0")echo 'private' ?>">
                        <td align="center">
                                <?php if($articleData['publish_status'] === "0"): ?>
                                    <i class="fa fa-solid fa-lock lock"></i>
                                <?php endif; ?>
                            <?php for($i=1; $i <= $articleData['rating'] ;$i++): ?>
                                <i class="fa fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </td>
                        <td><p class="cate_mark <?php echo $article->setClass(h($articleData['category']))?>"><?php echo h($articleData['category']) ?></p></td>
                        <td><?php echo h($articleData['title']) ?></td>
                        <td><?php echo date('Y年n月j日放送回', strtotime(h($articleData['on_air_date']))) ?></td>
                        <?php $comment_count = $article->getCommentCount(h($articleData['id'])) ?>
                        <td><i class="fa fa-comment"></i> <?php echo $comment_count[0]['count'] ?></td>
                        <td><a href="./update_form.php?id=<?php echo h($articleData['id']); ?>">編集</a></td>
                        <td><a href="../lib/article_delete.php?id=<?php echo h($articleData['id']); ?>">削除</a></td>
                    </tr>
                    <?php endforeach ?> 
                    <p><?php if(empty($articles))echo '検索結果はありません';  ?></p>                     
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
