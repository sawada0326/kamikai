<?php
require_once ('dbc.php');


Class Article extends Dbc {

    protected $table_name = 'articles';

    public function getBySameTitle($title ,$myId) {
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE title = :title AND NOT $this->table_name.id = $myId");
        $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);

        return $result;
    }

        
    public function getImage($title) {
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT path,program FROM images WHERE program = :title");
        $stmt->bindValue(':title', $title, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);

        return $result;
    }


    public function getTagSearch($tag) {
        $dbh = $this->dbConnect();
        //SQL準備
        $tag = "%".$tag."%";
        $stmt = $dbh->prepare("SELECT DISTINCT articles.* 
                               FROM articles,casts 
                               WHERE  articles.tags LIKE :tag
                               ORDER BY articles.on_air_date DESC");
        $stmt->bindValue(':tag', $tag, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);

        return $result;
    }

    
    public function getCategorySearch($search, $category) {
        $dbh = $this->dbConnect();
        //SQL準備
        $search = "%".$search."%";
        $stmt = $dbh->prepare("SELECT DISTINCT articles.* 
                               FROM articles,casts
                               WHERE  (articles.title OR LIKE :search)
                               AND articles.category = :category
                               ORDER BY articles.on_air_date DESC");
        $stmt->bindValue(':search', $search, \PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getSearch($search) {
        $dbh = $this->dbConnect();
        //SQL準備
        $search = "%".$search."%";
        $stmt = $dbh->prepare("SELECT DISTINCT articles.* 
                               FROM articles,casts 
                               WHERE  (articles.title LIKE :search OR articles.casts LIKE :search)
                               ORDER BY articles.on_air_date DESC");
        $stmt->bindValue(':search', $search, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getComments($id) {
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT * FROM comments WHERE comments.article_id = :article_id");
        $stmt->bindValue(':article_id', $id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);
        return $result;
    }


    public function getCommentCount($id) {
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT count(*) as 'count' FROM comments WHERE comments.article_id = :article_id");
        $stmt->bindValue(':article_id', $id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);
        return $result;
    }
    
    public static function setClass($category) {
        switch ($category) {
            case 'テレビ':
                return 'tv';
                break;
            case 'ラジオ':
                return 'radio';
                break;
            case 'アニメ':
                return 'anime';
                break;
        }
    }

    public function setArticleId($category) {
        switch ($category) {
            case 'テレビ':
                return '-1';
                break;
            case 'ラジオ':
                return '-2';
                break;
            case 'アニメ':
                return '-3';
                break;
        }
    }
    

    public function setTagName($tag) {
        switch ($tag) {
            case 1:
                return '#くだらない';
                break;
            case 2:
                return '#興味深い';
                break;
            case 3:
                return '#人間ドラマ';
                break;
            case 4:
                return '#社会派';
                break;
            case 5:
                return '#アクシデント';
                break;
            case 6:
                return '#マニア向け';
                break;
        }
    }

    public function articleCreate($articles, $file) {
        $articles['casts'] = implode(',', array_filter($articles['casts']));
        $articles['tags'] = implode(',', $articles['tags']);

        $sql = "INSERT INTO 
                    $this->table_name(category, broadcaster, title, caption, on_air_date, start_time, finish_time, casts, tags, rating, reason, publish_status)
                VALUES
                    (:category, :broadcaster, :title, :caption, :on_air_date, :start_time, :finish_time, :casts, :tags, :rating, :reason, :publish_status)";

        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            //articlesテーブル挿入
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':category', $articles['category'], PDO::PARAM_STR);
            $stmt->bindValue(':broadcaster', $articles['broadcaster'], PDO::PARAM_STR);
            $stmt->bindValue(':title', $articles['title'], PDO::PARAM_STR);
            $stmt->bindValue(':caption', $articles['caption'], PDO::PARAM_STR);
            $stmt->bindValue(':on_air_date', $articles['on_air_date'], PDO::PARAM_STR);
            $stmt->bindValue(':start_time', $articles['start_time'], PDO::PARAM_STR);
            $stmt->bindValue(':finish_time', $articles['finish_time'], PDO::PARAM_STR);
            $stmt->bindValue(':rating', (int)$articles['rating'], PDO::PARAM_INT);
            $stmt->bindValue(':casts', $articles['casts'], PDO::PARAM_STR);
            $stmt->bindValue(':tags', $articles['tags'], PDO::PARAM_STR);
            $stmt->bindValue(':reason', $articles['reason'], PDO::PARAM_STR);
            $stmt->bindValue(':publish_status', (int)$articles['publish_status'], PDO::PARAM_INT);
            $stmt->bindValue(':tags', $articles['tags'], PDO::PARAM_STR);
            $stmt->bindValue(':reason', $articles['reason'], PDO::PARAM_STR);
            $stmt->bindValue(':publish_status', (int)$articles['publish_status'], PDO::PARAM_INT);
            $stmt->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };
        //パスが受け渡されていたらimageを生成する
        if(isset($file['path'])) {
            if(!isset($articles['over_write'])) {
                $this->imageInsert($file, $articles['title']);
            } else {
                //ディレクトリ内のファイルを最初に削除する);
                $old_file = $this->getImage($articles['title']);
                if(!unlink($old_file[0]['path'])) {
                    $_SESSION['file_err'] = '画像ファイルの削除には失敗しました';
                };
                $this->imageUpdate($file, $articles['title']);
            }
        }

    }

    public function imageInsert($file, $program) {
        
        $sql_image =  "INSERT INTO
                            images (name, path, program)
                        VALUES
                            (:name, :path, :program)";

        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            $stmt = $dbh->prepare($sql_image);
            $stmt->bindValue(':name', $file['name'], PDO::PARAM_STR);
            $stmt->bindValue(':path', $file['path'], PDO::PARAM_STR);
            $stmt->bindValue(':program', $program, PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };

    }
    
    public function imageUpdate($file, $program) {

        $sql_image = "UPDATE images SET
                  name = :name, path = :path, program = :program
                WHERE
                  program = :program";

        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            $stmt = $dbh->prepare($sql_image);
            $stmt->bindValue(':name', $file['name'], PDO::PARAM_STR);
            $stmt->bindValue(':path', $file['path'], PDO::PARAM_STR);
            $stmt->bindValue(':program', $program, PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };

    }


    //記事のバリデーション
    public function articleValidate($articles, $file) {
        $title = $articles['title'];
        $casts = $articles['casts'];

        $err = [];

        if(empty($articles['category'])) {
            $err['e']['category'] = 'カテゴリーは必須です';
        }
        if(empty($articles['broadcaster'])) {
            $err['e']['broadcaster'] = '放送局は必須です';
        }
        if(empty($title)) {
            $err['e']['title'] = 'タイトルを入力してください';
        }
        if(mb_strlen($title) > 30) {
            $err['e']['title'] = 'タイトルを30文字以下にしてください';
        }
        if(empty($articles['caption'])) {
            $err['e']['caption'] = '見出しを入力してください';
        }
        if(mb_strlen($articles['caption']) > 30) {
            $err['e']['title'] = '見出しを50文字以下にしてください';
        }
        if(empty($articles['on_air_date'])) {
            $err['e']['on_air_date'] = '放送日を入力してください';
        }
        if(empty($articles['start_time']) || empty($articles['finish_time'])) {
            $err['e']['time'] = '放送時間を入力してください';
        }
        if($articles['rating'] === "0") {
            $err['e']['rating'] = '神回度は必須です';
        }
        if(!preg_match('/^[1-3]+$/', $articles['rating'])) {
            $err['e']['rating'] = '神回度は1~3で入力してください';
        }
        if(empty(array_filter($casts))) {
            $err['e']['casts'] = '出演者を一人以上入力してください';
        }
        if(mb_strlen($casts[0]) > 15 || mb_strlen($casts[1]) > 15 ||
           mb_strlen($casts[2]) > 15 || mb_strlen($casts[3]) > 15) {
            $err['e']['casts'] = '出演者は15文字以内で入力してください';
        }
        if(empty($articles['tags'])) {
            $err['e']['tags'] = 'タグを一つ以上入力してください';
        }
        if(empty($articles['reason'])) {
            $err['e']['reason'] = "選考理由を入力してください";
        }    
        //ファイルが挿入されていたらバリデーション
        if(!empty($file['img']['name'])) {
            $err = $this->imageValidate($file, $articles, $err);
        }

        if(count($err) == 0 || isset($err['none'])) {
            //入力した値を保持
            $_SESSION['input_data'] = $articles;
            //画像のバリデーションを通った場合
            if(isset($err['none'])) {
                $file['img']['path'] = $_SESSION['path'];
                $_SESSION['input_data'] += $file;
            }
            if(!strpos($_SERVER['HTTP_REFERER'],'update_form.php')) {
                header('Location: article_create.php');
                return;
            } else {
                header('Location: ../lib/article_update.php');
                return;
            }
        }
        $_SESSION += $err;
    }

    
    public function imageValidate($file, $articles, $err) {

        $img = $file['img'];
        $filename = basename($img['name']);
        $tmp_path = $img['tmp_name'];
        $file_err = $img['error'];
        $filesize = $img['size'];
        $allow_ext = array('jpg', 'jpeg', 'png');
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $upload_dir = "../images/";
        $save_filename = date('YmdHis'). $filename;
        $save_path = $upload_dir.$save_filename;
        $over_write = isset($articles['over_write']) ? $articles['over_write'] : null;
        $title = $articles['title'];
            
        if(!in_array(strtolower($file_ext),$allow_ext)) {
            $err['e']['file'] = '画像をアップロードしてください';
            return $err;
        }
        if(preg_match('/^[a-zA-Z0-9]+$/', $filename)) {
            $err['e']['name'] = 'ファイル名は英数字のみにしてください';
        }
        if($filesize > 1048576 || $file_err == 2) {
            $err['e']['file'] = 'ファイルサイズは1MB未満にしてください。';
        }
        //他のエラーがなくなってから画像を上げる準備をする
        if(count($err) !== 0)return $err;
        $program_list = $this->getImage($title);
        if(count($program_list) > 0 && $over_write !== "1") {
            $err['e']['distinct'] = "同じ番組名で使用されている画像ファイルが既にあります。";
            return $err;
        } 
        if (!is_uploaded_file($tmp_path)) {         
            $err['e']['file'] = 'ファイルのアップロードに失敗しました';
            return $err;
        }
        if(!move_uploaded_file($tmp_path, $save_path))  {     
            $err['e']['file'] = 'ファイルを保存できませんでした';
            return $err;
        }
        if($over_write == null || $over_write == "1") {      
            //パスを入れる
            $_SESSION['path'] = $save_path;
            $err['none'] = "compelete";
        }
        return $err;
    }

    public function articleUpdate($articles, $file) {
        $articles['casts'] = implode(',', array_filter($articles['casts']));
        $articles['tags'] = implode(',', $articles['tags']);
        
        $sql = "UPDATE $this->table_name SET
                    category = :category, broadcaster = :broadcaster, title = :title, caption = :caption, on_air_date = :on_air_date, start_time = :start_time, finish_time = :finish_time, rating = :rating, casts = :casts, tags = :tags, reason = :reason, publish_status = :publish_status
                WHERE
                    id = :id";
        
        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            //articlesテーブル挿入
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':category', $articles['category'], PDO::PARAM_STR);
            $stmt->bindValue(':broadcaster', $articles['broadcaster'], PDO::PARAM_STR);
            $stmt->bindValue(':title', $articles['title'], PDO::PARAM_STR);
            $stmt->bindValue(':caption', $articles['caption'], PDO::PARAM_STR);
            $stmt->bindValue(':on_air_date', $articles['on_air_date'], PDO::PARAM_STR);
            $stmt->bindValue(':start_time', $articles['start_time'], PDO::PARAM_STR);
            $stmt->bindValue(':finish_time', $articles['finish_time'], PDO::PARAM_STR);
            $stmt->bindValue(':rating', $articles['rating'], PDO::PARAM_INT);
            $stmt->bindValue(':casts', $articles['casts'], PDO::PARAM_STR);
            $stmt->bindValue(':tags', $articles['tags'], PDO::PARAM_STR);
            $stmt->bindValue(':reason', $articles['reason'], PDO::PARAM_STR);
            $stmt->bindValue(':publish_status', $articles['publish_status'], PDO::PARAM_INT);
            $stmt->bindValue(':id', $articles['id'], PDO::PARAM_INT);
            $stmt->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };
        
        //パスが受け渡されていたらimageを生成する
        if(isset($file['path'])) {
            $title = $articles['title'];
            $program_list = $this->getImage($title);
            if(count($program_list) === 0) {
                $this->imageInsert($file, $title);
            } else {
                //ディレクトリ内のファイルを最初に削除する);
                $old_file = $this->getImage($title);
                if(!unlink($old_file[0]['path'])) {
                    $_SESSION['file_err'] = '画像ファイルの削除には失敗しました';
                };
                $this->imageUpdate($file, $title);
            }
        }
    }


    public function commentsDelete($id) {
        if(empty($id)) {
            exit('idが不正です');
        }
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("DELETE FROM comments WHERE article_id = :article_id");
        $stmt->bindValue(':article_id', $id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();
    }

    public function imageDelete($program) {
        if(empty($program)) {
            exit('idが不正です');
        }
        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("DELETE FROM images WHERE program = :program");
        $stmt->bindValue(':program', $program, \PDO::PARAM_STR);
        
        //SQL実行
        $stmt->execute();
    }
    
}

?>