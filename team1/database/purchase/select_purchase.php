<?php

require_once __DIR__ . "/../connect/db_connect.php"; 
require_once __DIR__ . "/../user/check_logged_in.php"; // ログイン状態のチェック

// dbのproductデータをすべて返す関数
function get_purchase_from_db() {
    return select_all_purchase();
}

function select_all_purchase() {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('SELECT * FROM purchases'); // ユーザ情報を確認してから実行したほうがよさそう

    //sqlの実行
    $stmt->execute();
    $array = [];
    while ( $row = $stmt->fetch() ) {
        $tmp = [];
        foreach ( $row as $key => $val ) {
            if ( !empty($row[$key]) ) {
                array_push($tmp, $val);
            }
        }
        array_push($array, $tmp);
    }

    return $array;

}

