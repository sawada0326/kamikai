<?php
require_once('../env.php');

Class Dbc {
    protected $table_name;

    protected function dbConnect() {
        $host   = DB_HOST;
        $dbname = DB_NAME;
        $user   = DB_USER;
        $pass   = DB_PASS;

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        
        try {
            $dbh = new \PDO($dsn, $user, $pass,[
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (\PDOException $e) {
            echo "データベース接続確立エラー". $e->getMessage();
            exit();
        };

        return $dbh;

    }

    // 1.データ取得
    //　引数なし　返り値 result;
    public function getAll() {
        $dbh = $this->dbConnect();
        // SQL準備
        $sql = "SELECT * FROM $this->table_name WHERE publish_status = 1 ORDER BY id DESC";
        // SQL実行
        $stmt = $dbh->query($sql);
        // SQL受け取り
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }
    
    public function getAllAdmin() {
        $dbh = $this->dbConnect();
        // SQL準備
        $sql = "SELECT * FROM $this->table_name ORDER BY id DESC";
        // SQL実行
        $stmt = $dbh->query($sql);
        // SQL受け取り
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }


    public function getCategoryAll($category) {
        $dbh = $this->dbConnect();
        // SQL準備
        $sql = "SELECT * FROM $this->table_name WHERE publish_status = 1 AND category = :category ORDER BY id DESC";
        // SQL実行
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':category', $category, \PDO::PARAM_INT);
        //SQL実行
        $stmt->execute();
        $result = $stmt->fetchall(\PDO::FETCH_ASSOC);
        return $result;
        $dbh = null;
    }
    


    // 引数id
    // 返り値result 
    public function getById($id) {
        if(empty($id)) {
            exit('idが不正です');
        }

        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE id = :id AND publish_status = 1");
        $stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$result) {
            exit('ブログがありません');
        }

        return $result;
    }

    // 引数id
    // 返り値result 
    public function getByIdAdmin($id) {
        if(empty($id)) {
            exit('idが不正です');
        }

        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("SELECT * FROM $this->table_name WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();
        //結果を取得
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(!$result) {
            exit('ブログがありません');
        }

        return $result;
    }



    public function delete($id) {
        if(empty($id)) {
            exit('idが不正です');
        }

        $dbh = $this->dbConnect();
        //SQL準備
        $stmt = $dbh->prepare("DELETE FROM $this->table_name WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, \PDO::PARAM_INT);
        
        //SQL実行
        $stmt->execute();

    }

}


