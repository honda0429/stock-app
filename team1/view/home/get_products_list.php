<?php

function get_pro_list()
{
    /* データベースとのコネクションを開く
     * $dbb でPDOオブジェクトが使えるようになる
     */

    /* 💬 require で読み込んだ場合は、データベースとの通信終了後に $dbh = null を代入して
       * コネクションを切っておくと良いです
       * PHP実行のたびにSQL サーバーとのコネクションが増えてしまい、負荷がかかる可能性があります
       */
    require "../database/connect/db_connect.php"; //何回でも読み込む
    //global $dbh;

    /* プレースホルダつきSQLを作成する */
    $stmt = $dbh->prepare("SELECT id,name FROM products");

    try {
        $ret = $stmt->execute();

        if ($ret === true) {
            /* データ取得が成功していたら、trueを返す */
            return (["result" => true, "stmt" => $stmt]);
        } else {
            /* データ取得が失敗していたら、falseを返す */
            return (["result" => false, "stmt" => $stmt]);
        }
    } catch(PDOException $e) {
        return (["result" => false, "exeption" => $e]);
    }
    $dbh = null;
}

/* product_select()
 * データベースから取り出したレコードのステートメントオブジェクトを使って、
 * 画面に表示するtable要素を生成します
 */
function product_select($stmt)
{
    if ($stmt->rowCount() === 0) {
        return ("<tr><td colspan='3'>データがありません</td></tr>");
    }
    $sel = ""; /* 要素をまとめて入れておく変数 */
    while($item = $stmt->fetch()) {
        //		while($item = /* ステートメントオブジェクトから、1件ずつデータを取り出す */){
        $opt = "
            <option value={$item['id']}>
            {$item['id']}：{$item['name']}
            </option>
        ";
        $sel .= $opt;
    }
    $sel = "<select name='products'>".$sel."</select>"; //$selを<select>で囲む

    return ($sel); /* $elmsは、最初から最後まですべての$tr の内容を結合した内容になっている */
}
