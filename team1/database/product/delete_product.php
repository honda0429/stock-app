<?php

session_start();
// require_once "../connect/db_connect.php";
require_once __DIR__ . "/../connect/db_connect.php";

$in = parse_form();
delete_product($in);

function parse_form() {
    // global $in;
    // global $tmpl_dir;
    $in =[];

    $param = array();
    if ( isset($_GET) && is_array($_GET) ) { $param += $_GET; }
    if ( isset($_POST) && is_array($_POST ) ) { $param += $_POST; }
    
    foreach($param as $key => $val){
        if(is_array($val)){
            $val = array_shift($val);
        }
        $enc = mb_detect_encoding($val);
        $val = mb_convert_encoding($val,"UTF-8",$enc);
        $val = htmlentities($val,ENT_QUOTES,"UTF-8");
        $in[$key] = $val;
    }
    return $in;
}


function delete_product($in) {

    global $dbh;
 
    #エラーチェック
    $error_notes="";
    if ( empty($in["product_id"]) ) {
    // if($in["product_id"] == ""){
        $error_notes.="・削除する商品を選択してください。<br>";
    }
 
    #エラーが存在する場合
    if($error_notes != "") {
        error($error_notes);
    }
 
    # プリペアードステートメントを準備
    // $stmt = $dbh->prepare('DELETE FROM products WHERE id = :product_id');
    $stmt = $dbh->prepare('DELETE FROM products WHERE id = ?');
 
    # 変数を束縛する
    // $stmt->bindParam(':product_id', $product_id);
    $stmt->bindValue(1, $in["product_id"]);
 
    # 変数に値を設定し、SQL を実行
    // $product_id = $in["product_id"];
    $stmt->execute();

    // $url = __DIR__ . "../../view/home.php";
    // header("Location: {$url}");
    // echo "collect!!";

    var_dump($in);
    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "商品データを削除しました",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => '商品データの削除に失敗しました',
        ];
    }

    header("Location: ../../view/home.php#tabicon2");
    exit();
}

function error($message) {
    echo $message;
    die();
}
