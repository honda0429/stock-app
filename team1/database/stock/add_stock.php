<?php

// require_once "../database/connect/db_connect.php";
require_once __DIR__ . "/../connect/db_connect.php";

function add_stock($stock, $name){

    global $dbh;

    // $stmt_select = $dbh->prepare("SELECT name from products where id = ?;");
    // $stmt_select->bindValue(1, $product_id);
    // $stmt_select->execute();
    // $val = $stmt_select->fetch();
    // $name = $val[0];


    $stmt = $dbh->prepare("INSERT INTO stocks (name, stock) VALUES (?, ?)");
    // $stmt->bindValue(1, $product_id);
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $stock);
    $stmt->execute();
}

