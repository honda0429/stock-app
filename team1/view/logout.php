<?php
/*
 * ログアウト処理を行い、log.phpに遷移します
 */

session_start();

/* セッション情報を空にする */
$_SESSION = array();

/* Cookieに保存したセッションIDを削除 */
setcookie(session_name(), "", time() - 1);

/* セッションデータを破棄 */
session_destroy();

/* login.phpに遷移する */
/* 💬 log.php になっていますが、よいでしょうか？笑 */
header("Location: log.php");
