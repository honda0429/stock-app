<?php

require_once __DIR__ . "/../user/check_logged_in.php";
require_once __DIR__ . "/../connect/db_connect.php"; 

main_edit_stock();

// dbのproductデータを編集する関数
function main_edit_stock() {
    $data = parse_form();
    edit_stocks($data);
}

function parse_form() {
    // 要改良!!!
    // 暇なときにでも
    // POSTのデータを安全なものに変換してください
    $data = $_POST;
    return $data;
}

function edit_stock($data) {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('UPDATE stocks SET stock = :stock WHERE id = :id');
    
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':id', $id);

    
    $stock = $data["stock"];
    $id = $data["id"];

    //sqlの実行
    $stmt->execute();

    header("Location: home.php");
    

}

?>
