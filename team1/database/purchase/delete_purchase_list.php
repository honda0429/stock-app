<?php
/*
 * POSTで送信されたIDのレコードを削除する処理を行います
 */
session_start();

if (isset($_POST['delete_id'])) { /* isset($_POST['delete_id']) で、削除するidが指定されているか確認し、指定されている場合は処理を行う */
    require_once "../connect/db_connect.php";


    $stmt = $dbh->prepare("DELETE FROM purchases WHERE id = :id AND  user_id = :user_id"); //削除レコードを指定
//  $stmt = $dbh->prepare(" レコードを削除するためのプレースホルダ付きSQLを入力する ");
    $stmt->bindValue(':id',$_POST['delete_id']);
    $stmt->bindValue(':user_id',$_SESSION['login_id']);
    $stmt->execute();


//　フラッシュメッセージの表示    
    if ($stmt->rowCount() > 0) { /* 削除できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
        'type' => 'success',
//      $_SESSIONにフラッシュメッセージのタイプ(type)を設定する 
        'content' => "id: {$_POST['delete_id']}の仕入れ情報を削除しました",
//      $_SESSIONにフラッシュメッセージの内容(content)を設定する 
        ];
    } else { /* > 0 ではない場合(データを削除できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
        'type' => 'danger',
//      $_SESSIONにフラッシュメッセージのタイプ(type)を設定する 
        'content' => '存在しないタスクのIDが指定されました',
//       $_SESSIONにフラッシュメッセージの内容(content)を設定する 
        ];
    }
    /* $_POST['delete_id'] が送信されていた場合の処理、ここまで */

} else { /* $_POST['delete_id']が送信されていなかった場合、エラー扱いとする */
    $_SESSION['flush_message'] = [
        'type' => 'danger',
//      $_SESSIONにフラッシュメッセージのタイプ(type)を設定する 
        'content' => '存在しないタスクのIDが指定されました',
//      $_SESSIONにフラッシュメッセージの内容(content)を設定する 
    ];
}

/* 一覧画面に遷移する */
header("Location: ../../view/home.php");
