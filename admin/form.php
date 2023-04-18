<?php
    session_start();
    require_once ('../classes/UserLogic.php');
    require_once('../classes/article.php');
    require_once('../lib/functions.php');
    
    $result = UserLogic::checkLogin();
    if(!$result) {
        $_SESSION['msg'] = 'ログインしてください';
        header('Location: ../public/login_form.php');
        return;
    }
    //POSTの値がある かつ sessionにインプットされているデータがあるか
    if (!empty($_POST) && empty($_SESSION['input_data'])) {
        //二重送信防止,ワンタイムトークン
        $token = filter_input(INPUT_POST, 'csrf_token');
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            exit('不正なリクエストです');
        }
        unset($_SESSION['csrf_token']);
        $article = new Article();
        $article->articleValidate($_POST, $_FILES);
    } elseif (!empty($_SESSION['input_data'])) {
        $_POST = $_SESSION['input_data'];
    }

    //バリデーションメッセージ
    $err = isset($_SESSION['e']) ? $_SESSION['e'] : null;
    unset($_SESSION['e']);

    //入力値した値をそれぞれバリデーションから戻された後に出力する
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $caption = isset($_POST['caption']) ? $_POST['caption'] : null;
    $on_air_date = isset($_POST['on_air_date']) ? $_POST['on_air_date'] : null;
    $start_time = isset($_POST['start_time']) ? $_POST['start_time'] : null;
    $finish_time = isset($_POST['finish_time']) ? $_POST['finish_time'] : null;
    $broadcaster = isset($_POST['broadcaster']) ? $_POST['broadcaster'] : null;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
    $casts = isset($_POST['casts']) ? $_POST['casts'] : [];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    $reason = isset($_POST['reason']) ? $_POST['reason'] : null;
    $publish_status = isset($_POST['publish_status']) ? (int)$_POST['publish_status'] : 1;
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
        <h2>神回追加フォーム</h2>
        <form method="POST" enctype="multipart/form-data">
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
                    <input id="broadcaster" name="broadcaster" type="text" value="<?php if(!empty($broadcaster))echo h($broadcaster); ?>">
                </div>
                <?php if(isset($err['broadcaster'])): ?>
                        <p><?php echo $err['broadcaster']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>タイトル</label>
                    <input id="title" name="title" type="text" value="<?php if(!empty($title))echo h($title); ?>">
                </div>
                <?php if(isset($err['title'])): ?>
                        <p><?php echo $err['title']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>見出し</label>
                    <input id="caption" name="caption" type="text" value="<?php if(!empty($caption))echo h($caption); ?>">
                </div>
                <?php if(isset($err['caption'])): ?>
                        <p><?php echo $err['caption']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>放送日</label>
                    <input type="date" name="on_air_date" value="<?php if(!empty($on_air_date))echo h($on_air_date); ?>">
                </div>
                <?php if(isset($err['on_air_date'])): ?>
                        <p><?php echo $err['on_air_date']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>放送時間</label>
                    <input type="time" name="start_time" value="<?php if(!empty($start_time))echo h($start_time); ?>">
                    <span>〜</span>
                    <input type="time" name="finish_time" value="<?php if(!empty($finish_time))echo h($finish_time); ?>">
                </div>
                <?php if(isset($err['time'])): ?>
                        <p><?php echo $err['time']; ?></p>
                <?php endif ?>
                <div class="column">
                    <label>神回度</label>
                    <select name="rating">      
                        <option value="0" hidden>0</option>   
                        <option value="1" <?php if($rating === 1)echo "selected"; ?>>1</option>   
                        <option value="2" <?php if($rating === 2)echo "selected"; ?>>2</option>             
                        <option value="3" <?php if($rating === 3)echo "selected"; ?>>3</option>
                    </select>
                </div>
                <?php if(isset($err['rating'])): ?>
                        <p><?php echo $err['rating']; ?></p>
                <?php endif ?>
                <div id="cast_table" class="column">
                    <p>出演者</p>
                    <div>
                        <span><input type="text" name="casts[]" value="<?php if(isset($casts[0]))echo h($casts[0]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($casts[1]))echo h($casts[1]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($casts[2]))echo h($casts[2]); ?>"></span>
                        <span><input type="text" name="casts[]" value="<?php if(isset($casts[3]))echo h($casts[3]); ?>"></span>
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
                <div class="column">
                    <label>画像ファイル</label>
                    <input type="hidden" name="MAX_FILESIZE" value="1048576" />
                    <p><input name="img" type="file" accept="image/*" /></p>
                </div>
                <?php if(isset($err['file'])): ?>
                            <p><?php echo $err['file']; ?></p>
                <?php endif ?>
                <?php if(isset($err['name'])): ?>
                            <p><?php echo $err['name']; ?></p>
                <?php endif ?>
                <div id="content_table" class="column">
                    <p>選考理由</p>
                    <textarea name="reason" id="content" cols="70" rows="3"><?php if(!empty($reason))echo h($reason); ?></textarea>
                </div>
                <?php if(isset($err['reason'])): ?>
                            <p><?php echo $err['reason']; ?></p>
                <?php endif ?>
                <div id="stmt_table" class="column">
                    <label>公開状態：</label>
                    <input type="radio" name="publish_status" value="1" <?php if($publish_status === 1)echo "checked";; ?>>公開
                    <input type="radio" name="publish_status" value="0" <?php if($publish_status === 0)echo "checked"; ?>>非公開
                </div>
                <?php if(isset($err['publish_status'])): ?>
                            <p><?php echo $err['publish_status']; ?></p>
                <?php endif ?>
                <!-- 以下、タイトルが重複したら画像を上書きするか確認する -->
                <?php if(isset($err['distinct'])): ?>
                    <section>
                            <h3><?php echo $err['distinct']; ?></h3>
                                <div class="button_div">
                                    <input type="radio" name="over_write" value="1">上書きする
                                    <input type="radio" name="over_write" value="0" checked>上書きせず既定の画像を使用する
                                </div>
                            <input type="submit" id="button" value="送信">
                    </section>
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