<?php
    session_start();
    require_once ('../classes/UserLogic.php');
    require_once('../classes/article.php');
    require_once('../classes/request.php');
    require_once('../lib/functions.php');
    
    $result = UserLogic::checkLogin();

    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
    
    //POSTの値がある かつ sessionにインプットされているデータがあるか
    if (!empty($_POST) && empty($_SESSION['input_data'])) {
        $article = new Article();
        $article->articleValidate($_POST);
    } elseif (!empty($_SESSION['input_data'])) {
        $_POST = $_SESSION['input_data'];
    }

    //バリデーションメッセージ
    $err = isset($_SESSION['e']) ? $_SESSION['e'] : null;
    unset($_SESSION['e']);

    $request = new Request();
    $requestData = $request->getById($_GET['id']);
    
    //ポストされたらリクエストデータを置き換える
    $request_id = $requestData['id'];
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $broadcaster = isset($_POST['broadcaster']) ? $_POST['broadcaster'] : null;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : $requestData['title'];
    $on_air_date = isset($_POST['on_air_date']) ? $_POST['on_air_date'] : $requestData['on_air_date'];
    $reason = isset($_POST['reason']) ? $_POST['reason'] : $requestData['reason'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="../javascript/font.js"></script>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="shortcut icon" type="image/png" href="../images/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js" charset="utf-8"></script>
   
    <title>神回図鑑 | 追加フォーム</title>
</head>
<body>
    <header>
        <h1><a href="all.php">神回図鑑<span>-管理版-</span></a></h1>
        <form action="search.php" method="GET" >
            <div class="search_box">
                <input id="sear_box" type="text" value="" placeholder="番組名・出演者名を検索"　name="search"><input type="submit" id="sub_but" value="検索">
            </div>
        </form>
        <ul class="headerUL">
            <li><a href="request.php">リクエストポスト</a></li>
        </ul>
        <div class="menu_btn"><span></span><span></span></div>
    </header>
    <div class="container">
        <?php if(isset($err['aaaa'])): ?>
                <p><?php echo $err['aaaa']; ?></p>
        <?php endif ?>
        <h2>神回追加フォーム</h2>
        <form method="POST">
            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
            <div class="column_wrapper">
                <div class="column">
                <label>カテゴリ</label>
                    <select name="category" onchange="createChildOptions(this.form)">
                            <option value="0" hidden>カテゴリを選択して下さい</option>
                            <option value="テレビ" <?php if($category === "テレビ")echo "selected"; ?>>テレビ</option>
                            <option value="ラジオ" <?php if($category === "ラジオ")echo "selected"; ?>>ラジオ</option>
                            <option value="アニメ" <?php if($category === "アニメ")echo "selected"; ?>>アニメ</option>
                    </select>
                </div>
                <?php if(isset($err['category'])): ?>
                        <p><?php echo $err['category']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>放送局</label>
                    <input id="broadcaster" name="broadcaster" type="text" value="<?php if(!empty($_POST['broadcaster']))echo h($_POST['broadcaster']); ?>">
                </div>
                <?php if(isset($err['broadcaster'])): ?>
                        <p><?php echo $err['broadcaster']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>タイトル</label>
                    <input id="title" name="title" type="text" value="<?php echo $title; ?>">
                </div>
                <?php if(isset($err['title'])): ?>
                        <p><?php echo $err['title']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>見出し</label>
                    <input id="caption" name="caption" type="text" value="<?php if(!empty($_POST['caption']))echo h($_POST['caption']); ?>">
                </div>
                <?php if(isset($err['caption'])): ?>
                        <p><?php echo $err['caption']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>放送日</label>
                    <input type="date" name="on_air_date" value="<?php echo $on_air_date; ?>">
                </div>
                <?php if(isset($err['on_air_date'])): ?>
                        <p><?php echo $err['on_air_date']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>放送時間</label>
                    <input type="time" name="start_time" value="<?php if(!empty($_POST['start_time']))echo h($_POST['start_time']); ?>">
                    <span>〜</span>
                    <input type="time" name="finish_time" value="<?php if(!empty($_POST['finish_time']))echo h($_POST['finish_time']); ?>">
                </div>
                <?php if(isset($err['time'])): ?>
                        <p><?php echo $err['time']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>神回度</label>
                    <select name="rating">      
                        <option value="0" hidden>0</option>   
                        <option value="1" <?php if($rating === "1")echo "selected"; ?>>1</option>   
                        <option value="2" <?php if($rating === "2")echo "selected"; ?>>2</option>             
                        <option value="3" <?php if($rating === "3")echo "selected"; ?>>3</option>
                    </select>
                </div>
                <?php if(isset($err['rating'])): ?>
                        <p><?php echo $err['rating']; ?></p>
                <?php endif ?>
                <div id="cast_table" class="column">
                    <p>出演者</p>
                    <div>
                        <span><input type="text" name="casts[]" value="<?php if(isset($_POST['casts'][0]))echo h($_POST['casts'][0]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($_POST['casts'][1]))echo h($_POST['casts'][1]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($_POST['casts'][2]))echo h($_POST['casts'][2]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($_POST['casts'][3]))echo h($_POST['casts'][3]); ?>"></span>
                    </div>
                </div>
                <?php if(isset($err['casts'])): ?>
                        <p><?php echo $err['casts']; ?></p>
                <?php endif ?>
                <div id="tag_table" class="column">
                    <p>タグ</p>
                    <div>
                        <span><input type="checkbox" name="tags[]" value="1" <?php if(in_array('1', $tags))echo 'checked' ?>>#くだらない</span>
                        <span><input type="checkbox" name="tags[]" value="2" <?php if(in_array('2', $tags))echo 'checked' ?>>#興味深い</span>
                        <span><input type="checkbox" name="tags[]" value="3" <?php if(in_array('3', $tags))echo 'checked' ?>>#人間ドラマ</span>
                        <span><input type="checkbox" name="tags[]" value="4" <?php if(in_array('4', $tags))echo 'checked' ?>>#社会派</span>
                        <span><input type="checkbox" name="tags[]" value="5" <?php if(in_array('5', $tags))echo 'checked' ?>>#アクシデント</span>
                        <span><input type="checkbox" name="tags[]" value="6" <?php if(in_array('6', $tags))echo 'checked' ?>>#マニア向け</span>
                    </div>
                </div>
                <?php if(isset($err['tags'])): ?>
                            <p><?php echo $err['tags']; ?></p>
                <?php endif ?>
                <div id="stmt_table" class="column">
                    <label>公開状態：</label>
                    <input type="radio" name="publish_status" value="1" checked>公開
                    <input type="radio" name="publish_status" value="0">非公開
                </div>
                <?php if(isset($err['publish_status'])): ?>
                            <p><?php echo $err['publish_status']; ?></p>
                <?php endif ?>
                <div id="content_table" class="column">
                    <p>選考理由</p>
                    <textarea name="reason" id="content" cols="70" rows="3"><?php echo $reason ?></textarea>
                </div>
                <?php if(isset($err['reason'])): ?>
                            <p><?php echo $err['reason']; ?></p>
                <?php endif ?>
                <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                <input type="submit" id="button" value="送信">
            </div>
        </form>
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