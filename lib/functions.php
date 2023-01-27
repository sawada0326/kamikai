<?php
/**
 * XSS 対策
 * ポストは全部やる？
*/
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


/**
 * CSRF 対策
 * @param void
 * @return string $csrf_token
 */
function setToken() {
    // トークンを生成
    // フォームからそのトークンを送信
    // 送信後の画面でそのトークンを照会
    // トークンを削除
    // ポストは全部やる？
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
    
    return $csrf_token;
}

?>