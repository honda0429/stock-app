<?php

// require_once ""; // ログインチェックしときたい
// require_once "../../database/connect/db_connect.php"; 
require_once __DIR__ . "/../connect/db_connect.php"; 

// dbのproductデータをすべて返す関数
// 後々変更がありそうだから関数分けてるだけなので必要なければまとめてください
function get_products_from_db() {
    return select_all_products();
}

function select_all_products() {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('SELECT * FROM products'); // ユーザ情報を確認してから実行したほうがよさそう
    //sqlの実行
    $stmt->execute();
    $array = [];
    while ( $row = $stmt->fetch() ) {
        $tmp = [];
        foreach ( $row as $key => $val ) {
            if ( empty($row[$key]) ) {
                $tmp["product_{$key}"] = "no data";
            } else {
                $tmp["product_{$key}"] = $val;
            }
        }
        array_push($array, $tmp);
    }
    return $array;
}

