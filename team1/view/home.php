<?php
/*テンプレートの読み込み*/
$file_name = "../tmpl/home.tmpl";
$file_handler = fopen($file_name, "r");
$tmpl = fread($file_handler, filesize($file_name));

fclose($file_handler);


$tmpl_dir = "./";
session_start();

/*ログイン状態の確認*/

if(isset($_SESSION["logged_in"]) == false || $_SESSION["logged_in"] != true) {
    header("Location:log.php");
}

include_once "../include.php";  //フラッシュメッセージ
include_once "./home/purchase_list.php"; //仕入れ情報タブ
include_once "./home/get_products_list.php"; //商品データ取得
require_once "home/products_list.php";
require_once "home/stocks_list.php";
$product_tmpl =  products_list();
//echo $product_tmpl;
$stock_tmpl =  stocks_list();
//echo $stock_tmpl;




/*仕入れ情報タブ*/
$res = get_purchase_list();

//var_dump($res);

if ($res["result"] === true) {
    /* データの取得に成功していた場合、htmlに埋め込むtable要素の内容を作る */
    $list_items = purchase_table($res["stmt"]);
} else {
    /* データベースからレコードが取得できなかったら、$elmにはエラーメッセージを入れておく */
    $list_items = "<tr><td class='alert alert-danger' colspan='3'>データの取得に失敗しました</td></tr>";
}

/*商品データ取得*/
$sel = get_pro_list();

if ($sel["result"] === true) {
    /* データの取得に成功していた場合、htmlに埋め込むtable要素の内容を作る */
    $pro_items = product_select($sel["stmt"]);
} else {
    /* データベースからレコードが取得できなかったら、$elmにはエラーメッセージを入れておく */
    $pro_items = "<tr><td class='alert alert-danger' colspan='3'>データの取得に失敗しました</td></tr>";
}


$flush="";
$flush = put_flush_message($flush);
$tmpl = str_replace("!user_name!", $_SESSION["login_name"], $tmpl);
/*文字変換*/
//フラッシュメッセージ
$tmpl=str_replace("!flush_message!", $flush, $tmpl);
//在庫情報
$tmpl=str_replace("!stocks_list!", $stock_tmpl, $tmpl);
//商品データ
$tmpl=str_replace("!products_list!", $product_tmpl, $tmpl);
//仕入れ情報
$tmpl=str_replace("!pro_items!", $pro_items, $tmpl);
$tmpl=str_replace("!list_items!", $list_items, $tmpl);

echo $tmpl;
exit;

?>

