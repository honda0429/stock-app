<?php

    require_once __DIR__ . "/../user/check_logged_in.php";
    require_once __DIR__ . "/../connect/db_connect.php";

    function search_products_from_db($keyword) {
        global $dbh;
        $keywords = explode(" ", $keyword);
        $len = count($keywords);
        $tmp = "name like ?";
        $sentences = [];
        for ( $i = 0; $i < $len; $i++ ) {
            array_push($sentences, $tmp);
        }
        $sentence = implode(" OR ", $sentences);
        $stmt = $dbh->prepare("SELECT * FROM products WHERE {$sentence};");
        for ( $i = 0; $i < $len; $i++ ) {
            $stmt->bindValue($i+1, "%{$keywords[$i]}%");
        }

        $stmt->execute();
        $array = [];
        while ( $row = $stmt->fetch() ) {
            $tmp = [];
            foreach ( $row as $key => $val ) {
                if ( empty($row[$key]) ) {
                    $tmp["product_{$key}"] = "no data";
                } else {
                    $tmp["product_{$key}"] = $val;
                }
            }
            array_push($array, $tmp);
        }

        return $array;
    }

?>
