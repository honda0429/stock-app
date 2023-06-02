<?php

require_once __DIR__ . "/../user/check_logged_in.php";
require_once __DIR__ . "/../connect/db_connect.php"; 

main_delete_stock();

// dbのproductデータを編集する関数
function main_edit_stock() {
    $data = parse_form();
    delete_stock($data);
}

function parse_form() {
    // 要改良!!!
    // 暇なときにでも
    // POSTのデータを安全なものに変換してください
    $data = $_POST;
    return $data;
}

function delete_stock($data) {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('DELETE FROM stocks WHERE id = :id');
 
    $stmt->bindParam(':id', $id);
    
    $id = $data["stock_id"];

    //sqlの実行
    $stmt->execute();

    header("Location: home.php");
}

?>
