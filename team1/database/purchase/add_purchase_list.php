<?php
/*
 * POSTで送信されたtasks のデータを追加する処理を行います
 */
session_start();




if (isset($_POST)) { /* isset() で、$_POSTが送信されているか確認し、送信されている場合はレコードの追加を行う */
    require_once "../connect/db_connect.php";

    
    //1. SELECT で対象のデータをとる
    // productsのidとpurchaseのidを内部結合
    /*
    $select_sql = "SELECT purchases.product_id FROM products INNER JOIN purchases ON products.id = purchases.product_id WHERE products.name = ?";
    // クエリを実行
    $stmt = $dbh->prepare($select_sql);
    $stmt->execute([$_POST['product_name']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);  //$itemに$stmtの内容を読み込む
    */

    /*2サブクエリを使う
    "INSERT INTO purchase (product_id,quantity, date, note, user_id, done ) VALUES(
        (SELECT purchases.product_id INNER JOIN products.id = purchases.product_id WHERE product.name = ?),
    ?, ?, ?, ?, 0);";
    */

    /*
    $sql = "INSERT INTO purchases (product_id,quantity, date, note, user_id, done ) VALUES(?,?, ?, ?, ?, 0)"; 
    $stmt = $dbh->prepare($sql); //$stmtに$sqlの内容を読み込む
    $stmt->execute([$item,$_POST['quantity'], date("Y/m/d H:i:s"),$_POST['note'],$_SESSION['']]); 
    */
    //特殊文字処理
    $products = $_POST['products'];
    $quantity = $_POST['quantity'];
    $note = $_POST['note'];
    $products = htmlentities($products,ENT_QUOTES,"utf-8");
    $quantity = htmlentities($quantity,ENT_QUOTES,"utf-8");
    $note = htmlentities($note,ENT_QUOTES,"utf-8");

    $sql = "INSERT INTO purchases (product_id,quantity, date, note, user_id, done ) VALUES(?,?, ?, ?, ?, 0)"; 
    $stmt = $dbh->prepare($sql); //$stmtに$sqlの内容を読み込む
    $stmt->execute([$products,$quantity, date("Y/m/d H:i:s"),$note,$_SESSION['login_id']]); 

    
    /* $stmt->rowCount() はexecuteで実行したSQLが影響したデータベースレコードの件数を取得する
     * これを使って、データの更新ができたかを確認し、フラッシュメッセージをセットする
     */
    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "仕入れ情報を追加しました",
        ];

    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => '仕入れ情報の追加に失敗しました',
        ];
    }
    /* $_POST データが送信されていた場合の処理、ここまで */

} else { /* $_POSTが送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
        'content' => 'データが送信されていません',
    ];
}

 
/* 一覧画面に遷移する */
header("Location:  ../../view/home.php");
?>
