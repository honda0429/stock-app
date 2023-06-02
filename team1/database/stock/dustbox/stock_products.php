<?php

require_once "../database/connect/db_connect.php"; 
require_once "../database/user/check_logged_in.php"; // ログイン状態のチェック

// dbのproductデータをすべて返す関数
function get_stocks_from_db() {
    return select_all_products();
}

function select_all_stocks() {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('SELECT * FROM stocks'); // ユーザ情報を確認してから実行したほうがよさそう

    //sqlの実行
    $stmt->execute();
    $array = [];
    while ( $row = $stmt->fetch() ) {
        $tmp = [];
        // idはなければスキップ
        if ( empty($row["id"]) ) {
            continue;
        } else {
            $tmp["id"] = $row["id"];
        }
        // 商品名はなければno data
        if ( empty($row["name"]) ) {
            $tmp["stock_name"] = "no data";
        } else {
            $tmp["stock_name"] = $row["name"];
        }
        // 在庫はなければ0
        if ( empty($row["stock"]) ) {
            $tmp["stock_number"] = 0;
        } else {
            $tmp["stock_number"] = $row["stock"];
        }
        array_push($array, $tmp);
    }
    return $array;

}
