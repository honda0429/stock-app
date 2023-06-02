<?php

require_once "../database/connect/db_connect.php";
function get_purchase_list()
{
    /* データベースとのコネクションを開く
     * $dbb でPDOオブジェクトが使えるようになる
     */

    global $dbh;

    /* プレースホルダつきSQLを作成する */
    $stmt = $dbh->prepare("SELECT * FROM purchases");//ここでwhereを使って1,0とすればいい

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
}

/* purchase_table()
 * データベースから取り出したレコードのステートメントオブジェクトを使って、
 * 画面に表示するtable要素を生成します
 */
function purchase_table($stmt)
{
    if ($stmt->rowCount() === 0) {
        return ("<tr><td colspan='3'>データがありません</td></tr>");
    }
    $elms = ""; /* tableの要素をまとめて入れておく変数 */
    while($item = $stmt->fetch()) {
        if($item['done'] == 1) {
            continue;
        }
        //		while($item = /* ステートメントオブジェクトから、1件ずつデータを取り出す */){
        $tr = "<tr>
			<td class='align-top'>{$item['id']}</td>
			<td class='align-top'>{$item['user_id']}</td>
            <td class='align-top'>{$item['product_id']}</td>
			<td class='align-top'>{$item['quantity']}</td> 
            <td class='align-top'>{$item['date']}</td>
            <td class='align-top'>{$item['note']}</td>
            
			<td class='align-middle'>
					<form action='../database/purchase/done_purchase_list.php' method='POST' class='d-inline-block'>
						<button type='submit' name='done_id' value='{$item['id']}' ><i class='fas fa-check-circle'></i></button>
						<input type='hidden' name='product_id' value='{$item['product_id']}'>
						<input type='hidden' name='quantity' value='{$item['quantity']}'>
					</form>
					<form action='../database/purchase/delete_purchase_list.php' method='POST' class='d-inline-block'>
						<button type='submit' name='delete_id' value='{$item['id']}'><i class='fas fa-trash-alt'></i></button>
					</form>
			</td>
		</tr>";

        /* $elmsに、今回の処理で作成した$trの内容を追記する */
        $elms .= $tr;
    }
    return ($elms); /* $elmsは、最初から最後まですべての$tr の内容を結合した内容になっている */
}
