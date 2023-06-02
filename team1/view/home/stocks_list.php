<?php

    // require_once "../../database/user/check_logged_in.php"; // ログインチェック
    require_once __DIR__ . "/../../database/stock/select_stocks.php";
    $file_path = __DIR__ . "/../../";

    // この関数を呼び出して在庫データ一覧のテーブルを取得
    // <div><table>....</table></div>状態の要素を返却
    function stocks_list() {
        global $file_path;
        $tmpl = read_file_stocks("{$file_path}tmpl/stock/stock_list_logged_in.tmpl");
        // 在庫データ一覧の取得
        $array = get_stocks_list();
        $stock_rows = array_to_stocks_table($array);
        return str_replace("!stock_rows!", $stock_rows, $tmpl);
    }

    function array_to_stocks_table($array) {
        global $file_path;
        $tmpl = read_file_stocks("{$file_path}tmpl/stock/stock_row.tmpl");
        $table = "";
        foreach ( $array as $stock ) {
            $tmp = $tmpl;
            foreach ( $stock as $key => $val ) {
                $tmp = str_replace("!{$key}!", $val, $tmp);
            }
            $table .= $tmp;
        }
        return $table;
    }

    function read_file_stocks($file_name) {
        $file_handler = fopen($file_name, "r");
        $tmpl = fread($file_handler, filesize($file_name));
        fclose($file_handler);
        return $tmpl;
    }

    function get_stocks_list() {
        // データベースから商品データの配列を受け取る
        $stocks = get_stocks_from_db();
        return $stocks;
    }

?>
