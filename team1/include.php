<?php


/* 直前の処理で発生したフラッシュメッセージを、画面上に表示する */
function put_flush_message($result){
	/* セッションが開始していなければ、session_start()を実行する */
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	/* flush_messageが設定されている場合、画面に表示する */
	
	if (isset($_SESSION['flush_message'])) { /* isset($_SESSION['flush_message']) で、フラッシュメッセージがセットされているか確認する */
		$result = "{$_SESSION['flush_message']['content']}";

		/* 一度出力したら、次は表示しないようにセッションからフラッシュメッセージを除去する */
		unset($_SESSION['flush_message']);
	}
	return($result);
}
?>
