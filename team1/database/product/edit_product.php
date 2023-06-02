<?php

session_start();
// require_once "../database/user/check_logged_in.php";
// require_once "../database/connect/db_connect.php"; 
require_once __DIR__ . "/../user/check_logged_in.php";
require_once __DIR__ . "/../connect/db_connect.php"; 

main_edit_products();

// dbのproductデータを編集する関数
function main_edit_products() {
    $data = parse_form();
    edit_products($data);
}

function parse_form() {
    // 要改良!!!
    // 暇なときにでも
    // POSTのデータを安全なものに変換してください
    $data = $_POST;
    return $data;
}

function edit_products($data) {
    global $dbh;
    # プリペアードステートメントを準備
    $stmt = $dbh->prepare('UPDATE products SET name = :name, price = :price, manufacturer = :manufacturer, date = :date WHERE id = :id');
    
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':manufacturer', $manufacturer);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':id', $id);

    
    $name = $data["product_name"];
    $price = $data["price"];
    $manufacturer = $data["manufacturer"];
    $date = $data["date"];
    $id = $data["product_id"];

    //sqlの実行
    $stmt->execute();
    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "商品データの編集を確定しました",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => '商品データの編集の確定に失敗しました',
        ];
    }

    header("Location: ../../view/home.php");

}

?>
