<?php


session_start();
// require_once "../database/connect/db_connect.php";
require_once __DIR__ . "/../connect/db_connect.php";

$in = parse_form() ;
add_product($in);
header('Location:done_add_product.php');

function parse_form(){
    $in =[];

    $param = array();
    if(isset($_GET) && is_array($_GET)){$param += $_GET;}
    if(isset($_POST) && is_array($_POST)){$param += $_POST;}

    
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

function add_product($in){

    global $dbh;

    $error_notes="";
    if( empty($in["product_name"]) ){
        $error_notes.="・商品名が未入力です。<br>";
    }
    if( empty($in["price"]) ){
        $error_notes.="・値段が未入力です。<br>";
    }
 
    if($error_notes != "") {
        error($error_notes);
    }
 
    $stmt = $dbh->prepare("INSERT INTO products (name, price, manufacturer, date) VALUES (:name,:price,:manufacturer,:date)");
 
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':manufacturer', $manufacturer);
    $stmt->bindParam(':date', $date);
 
    $name = $in["product_name"];
    $price = $in["price"];
    $manufacturer = $in["manufacturer"];
    $date = $in["date"];


    $stmt->execute();
    if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "商品データを追加しました",
        ];
    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => '商品データの追加に失敗しました',
        ];
    }


    header("Location: ../../view/home.php");
    exit();

}

