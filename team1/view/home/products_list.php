<?php

    require_once __DIR__ . "/../../database/user/check_logged_in.php"; // ログインチェック
    require_once __DIR__ . "/../../database/product/select_products.php";
    require_once __DIR__ . "/../../database/product/search_products.php";
    $file_path = __DIR__ . "/../../";

    // $tmp =  products_list();
    // echo $tmp;

    // この関数を呼び出して商品データ一覧のテーブルを取得
    // <div><table>....</table></div>状態の要素を返却
    function products_list() {
        global $file_path;
        $tmpl = read_file("{$file_path}tmpl/product/products_list.tmpl");
        // 商品データ一覧の取得
        // $user_id = $_SESSION["usr_id"];
        $array = get_products_list();
        $product_rows = array_to_products_table($array);
        return str_replace("!product_rows!", $product_rows, $tmpl);
    }

    function array_to_products_table($array) {
        global $file_path;
        $tmpl = read_file("{$file_path}tmpl/product/product_row.tmpl");
        $table = "";
        foreach ( $array as $product ) {
            $tmp = $tmpl;
            foreach ( $product as $key => $val ) {
                $tmp = str_replace("!{$key}!", $val, $tmp);
            }
            $table .= $tmp;
        }
        return $table;
    }

    function read_file($file_name) {
        $file_handler = fopen($file_name, "r");
        $tmpl = fread($file_handler, filesize($file_name));
        fclose($file_handler);
        return $tmpl;
    }

    function get_products_list() {
        if ( !isset($_GET["search_product_keyword"]) ) {
            // データベースから商品データの配列を受け取る
            $products = get_products_from_db();
            return $products;
            // return [...$products, []];
        }
        $keyword = htmlentities($_GET["search_product_keyword"], ENT_QUOTES, "utf-8");
        $products = search_products_from_db($keyword);
        return $products;
    }

?>

