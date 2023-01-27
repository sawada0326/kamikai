<?php
require_once('dbc.php');

Class Comment extends Dbc {
    protected $table_name = 'comments';

    public function commentPost($comments) {
        
        $sql = "INSERT INTO
                        comments(article_id, name, text)
                    VALUES
                        (:article_id, :name, :text);";

        
        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            //commentsテーブル挿入
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':article_id', $comments['article_id'], PDO::PARAM_INT);
            $stmt->bindValue(':name', $comments['name'], PDO::PARAM_STR);
            $stmt->bindValue(':text', $comments['text'], PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();
            $transiton = $comments['article_id'];
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };
    }
    public function commentValidate($comments) {
        $err = [];

        if(empty($comments['text']) || ctype_space($comments['text'])) {
            $err['e']['text'] ='本文を入力してください';
        }
        if(empty($comments['name']) || ctype_space($comments['text'])) {
            $comments['name'] = '名無し太郎';
        }
        if(count($err) == 0) {
            $_SESSION['input_data'] = $comments;
            header('Location: ../lib/comment_post.php');
            exit();
        }

        $_SESSION = $err;

        header('Location: ' . $_SERVER['HTTP_REFERER'] . "#box");
        exit();
    }
}
?>