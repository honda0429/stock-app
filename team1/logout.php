<?php
/*
 * ログアウト処理を行い、login.phpに遷移します
 */

session_start();

/* セッション情報を空にする */
$_SESSION = array();

/* Cookieに保存したセッションIDを削除 */
setcookie(session_name(), "", time() - 1);

/* セッションデータを破棄 */
session_destroy();

/* log.phpに遷移する */
header("Location: log.php");
?>
