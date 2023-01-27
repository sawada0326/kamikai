<?php 
require_once ('dbc.php');

Class UserLogic extends Dbc {
    protected $table_name = 'administer';

    /**
     * ログイン処理
     * @param string $login_id, 
     * @param string $_POST
     * @return bool $result
     */
    public function PasswordChangeValidate($login_id ,$pass) {

        if(empty($pass['old_password'])) {
            $_SESSION['e']['old'] = '元のパスワードを記入してください';
        }
        if(empty($pass['new_password'])) {
            $_SESSION['e']['new'] = '新規パスワードを記入してください';
        }
        if($pass['new_password_conf'] != $pass['new_password']) {
            $_SESSION['e']['conf'] = '確認パスワードが違います';
            //入力を空にする
            $pass['new_password_conf'] = null;
        }
    
        if(count($_SESSION['e']) == 0) {
            $_SESSION['input_data'] = $_POST;
            
            $result = $this->changePassword($login_id, $pass);
            unset($_SESSION['input_data']);
            //ログインユーザ以外のセッションを削除
            if($result) {
                $save = $_SESSION['login_user'];
                $_SESSION = array();
                $_SESSION['login_user'] = $save;
                $_SESSION['complete'] = 'パスワード変更が完了しました';
            }else {
                $_SESSION['msg'] = 'パスワード変更に失敗しました';
            }
        }
    }


    /**
     * ログイン処理
     * @param string $login_id
     * @param string $password
     * @return bool $result
     */
    public function changePassword($login_id, $pass) {
      //パスワードを照会
      $result = $this->passwordVerify($login_id, $pass['old_password']);
      if(!$result)return $result;

      $sql = "UPDATE $this->table_name SET
        login_id = ?, password = ?
            WHERE
        login_id = ?";

      // ユーザデータを配列に入れる
      $arr = [];
      $arr[] = $login_id;
      $arr[] = password_hash($pass['new_password'], PASSWORD_DEFAULT);
      $arr[] = $login_id;

      try {
        $stmt = $this->dbConnect()->prepare($sql);
        $result = $stmt->execute($arr);
        return $result;
      } catch(\Exception $e) {
        echo $e; // エラーを出力
        return $result;
      }
    }

    /**
     * ログイン処理
     * @param string $login_id, password
     * @param string $password
     * @return bool $result
     */
    public function passwordVerify($login_id, $oldpass) {
        //結果
        $result = false;
        // ユーザをlogin_idから検索して取得
        $administer = $this->getAdministerByLoginId($login_id);

        if (!$administer) {
            exit ('不正なリクエストです');
        }
        // パスワードの照会
        if (password_verify($oldpass, $administer['password'])) {
            $result = true;
            return $result;
        }
        $_SESSION['e']['old'] = '元のパスワードが違います';
        return $result;

    }


    /**
     * ログイン処理
     * @param string $login_id
     * @param string $password
     * @return bool $result
     */
    public function login($login_id, $password) {
        //結果
        $result = false;
        // ユーザをlogin_idから検索して取得
        $administer = $this->getAdministerByLoginId($login_id);

        if (!$administer) {
            $_SESSION['msg'] = 'ログインIDかパスワードが違います。';
            return $result; 
        }
        // パスワードの照会
        if (password_verify($password, $administer['password'])) {
            //ログイン成功
            session_regenerate_id(true);
            $_SESSION['login_user'] = $administer;
            $result = true;
            return $result;
          }
        $_SESSION['msg'] = 'ログインIDかパスワードが違います。';
        return $result;

    }
    /**
     * ログインIDから管理者を取得
     * @param string $login_id
     * @param string $password
     * @return array|bool $administer|false
     */
    public function getAdministerByLoginId($login_id) {

        $sql = 'SELECT * FROM administer WHERE login_id = ?';

        //login_idを配列に入れる
        $arr = [];
        $arr[] = $login_id;

        try {
            $dbh = $this->dbConnect();
            $stmt = $dbh->prepare($sql);
            $stmt->execute($arr);
            //SQLの結果を返す
            $administer = $stmt->fetch();
            return $administer;
        } catch(\Exception $e) {
            return false;
        }
    }
   
    /**
     * ログインチェック
     * @param void
     * @return bool $result
     */
    public static function checkLogin() {
        $result = false;

        //セッションにログインユーザが入ってなかったらfalse
        if(isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0) {
            return $result = true;
        }

        return $result;
    }

    public static function logout() {
        $_SESSION = array();
        session_destroy();
    }

}

?>