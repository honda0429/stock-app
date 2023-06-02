

<?php

    // require_once "../database/product/select_products.php";
    require_once __DIR__ . "/../database/stock/select_stocks.php";

    $tmpl_path = "../";
    top_page();
    // home_page();

    function read_file($file_name) {
        $file_handler = fopen($file_name, "r");
        $tmpl = fread($file_handler, filesize($file_name));
        fclose($file_handler);
        return $tmpl;
    }

    // トップページを表示する関数
    function top_page() {
        global $tmpl_path;
        $tmpl = read_file("{$tmpl_path}/tmpl/toppage.tmpl");
        $stocks = stocks();
        $html = str_replace("!stocks!", $stocks, $tmpl);
        echo $html;
    }
    // function  home_page(){
    //         global $tmpl_path;
    //         $tmpl = read_file("{$tmpl_path}home.tmpl");
    //         $stocks = stocks();
    //         $html = str_replace("!stocks!", $stocks, $tmpl);
    //         echo $html;
    // }
    
        
 

    // stockのテーブルを作成する関数
    function stocks() {
        global $tmpl_path;
        $tmpl = read_file("{$tmpl_path}tmpl/stock/stocks.tmpl");
        $stock_row_array = stock_row();
        $stock_rows = implode("", $stock_row_array);
        return str_replace("!stock_row!", $stock_rows, $tmpl);
    }

    // stockのデータを作成する関数
    function stock_row() {
        global $tmpl_path;
        $tmpl = read_file("{$tmpl_path}tmpl/stock/stock_row.tmpl");
        $stock_array = get_stocks();
        $stock_row = [];
        foreach ( $stock_array as $stock) {
            $stock_row_tmpl = $tmpl;
            foreach ( $stock as $key => $val ) {
                $stock_row_tmpl = str_replace("!{$key}!", $val, $stock_row_tmpl);
            }
            array_push($stock_row, $stock_row_tmpl);
        }
        return $stock_row;
    }

    // databaseからデータを受け取る関数からデータを受け取る関数
    function get_stocks() {
        // データベースから商品データ一覧を取得
        $stocks = get_stocks_from_db();
        return $stocks;
    }


?>
