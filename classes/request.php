<?php
require_once('dbc.php');

Class Request extends Dbc {
    protected $table_name = 'requests';

    public function RequestPost($requests) {
        
        $sql = "INSERT INTO
                        requests(title, on_air_date, reason)
                    VALUES
                        (:title, :on_air_date, :reason);";

        
        $dbh = $this->dbConnect();
        $dbh->beginTransaction();
        try {
            //requestsテーブル挿入
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':title', $requests['title'], PDO::PARAM_STR);
            $stmt->bindValue(':on_air_date', $requests['on_air_date'], PDO::PARAM_STR);
            $stmt->bindValue(':reason', $requests['reason'], PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            exit($e);
        };
    }

    public function RequestValidate($requests) {
        $err = [];

        if(empty($requests['title'])) {
            $err['e']['title'] = 'タイトルを入力してください';
        }
        
        if(empty($requests['on_air_date'])) {
            $_POST['on_air_date'] = date('9999-12-31');
        }

        if(count($err) == 0) {
            $_SESSION['input_data'] = $_POST;
            header('Location: ../lib/request_post.php');
            exit();
        }

        $_SESSION += $err;
    }
}
?>