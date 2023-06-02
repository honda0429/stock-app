<?php

require_once __DIR__ . "/../connect/db_connect.php";
require_once __DIR__ . "/../stock/add_stock.php";

function update_stock($product_id, $quantity)
{
    $name = get_name_from_product($product_id);
    $data = select_stock($product_id, $quantity);
    if ($data["exist"]) {
        update_stocks($product_id, $data["stock"], $name);
    } else {
        add_stock($data["stock"], $name);
    }
}

function get_name_from_product($product_id)
{
    global $dbh;
    $stmt_product = $dbh->prepare("SELECT name from products where id = ?;");
    $stmt_product->bindValue(1, $product_id);
    $stmt_product->execute();
    $val = $stmt_product->fetch(PDO::FETCH_ASSOC);
    return $val['name'];
}

function select_stock($product_id, $quantity)
{
    global $dbh;
    /* 💬 $product_id をバインドせず、そのまま変数代入していますが、これは安全な値でしょうか？
     * ブラウザから送信されてきた値を利用している可能性はないでしょうか？
     */

    $stmt = $dbh->prepare("SELECT stock from stocks where id = ?;");
    $stmt->bindValue(1, $product_id);
    $res = $stmt->execute();
    $stock = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->bindValue(1, $product_id);

    if (!$res || !$stock) {
        $data["exist"] = false;
        $data["stock"] =$quantity;
        return $data;
    }
    $data["exist"] = true;
    $data["stock"] =$quantity+$stock['stock'];

    return $data;
}

function update_stocks($product_id, $stock, $name)
{
    global $dbh;

    /* 💬 stocks テーブル上に存在しない 商品が指定された場合に追加できない可能性があります
     * プロジェクト内で、INSERT INTO stocks を行っている箇所が見当たらないため、
     * 新しい商品を追加 -> その商品の仕入れデータを追加 -> 完了を押してstocks テーブルの在庫数に反映　を行った時に、
     * 新しい商品の在庫が増えないという問題が発生する可能性があります
     */
    $stmt = $dbh->prepare("UPDATE stocks SET stock = ?, name = ? WHERE id = ?");

    $stmt->bindValue(1, $stock);
    $stmt->bindValue(2, $name);
    $stmt->bindValue(3, $product_id);

    $stmt->execute();
}
