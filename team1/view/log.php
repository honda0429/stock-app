<?php

// tmplファイルの読み込み
/*
$file_name = "../login.tmpl";
$file_handler = fopen($file_name,"r");
$tmpl = fread($file_handler,filesize($file_name));
fclose($file_handler);

echo $tmpl;
*/
// login.php
/*
$file_name = "../login.tmpl";
$file_handler = fopen($file_name,"r");
$tmpl = fread($file_handler,filesize($file_name));
fclose($file_handler);

echo $tmpl;
*/

session_start(); 
$err_msg = "";

//ログイン状態のチェック
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: home.php");
}

//POSTデータの有無のチェック
if (empty($_POST) === false) {
    if (empty($_POST["user_name"]) === false || empty($_POST["password"]) === false) {
        $err_msg = auth_check();
    } else {
        $err_msg = "<p>ID・パスワードを入力してください</p>";
    }
}



//値を照合しログイン判定を行う
function auth_check()
{
    require_once "../database/connect/db_connect.php"; 

    try {
        $stmt = $dbh->prepare("SELECT id, password FROM users WHERE name = ?");
        $stmt->execute([$_POST["user_name"]]); 
        $correct_user = $stmt->fetch();  

        if ($stmt->rowCount() > 0) { 
            if (md5($_POST["password"]) === $correct_user["password"]) {
                $_SESSION["login_id"] = $correct_user["id"];
                $_SESSION["login_name"] = $_POST["name"];
                $_SESSION["logged_in"] = true;

                $dbh = null;
                header("Location: home.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit();
    }

    $dbh = null;
    $_SESSION["logged_in"] = false;
    return ("IDまたはパスワードが正しくありません");
}
?>

<!DOCTYPE HTML>
<html>

<head>
	<meta charset="utf-8">
	<title>ログインページ</title>
	<link rel="stylesheet" href=../css/login.css>
    <link href="https://fonts.googleapis.com/earlyaccess/kokoro.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho" rel="stylesheet">

</head>
<body>
	<?php
/* エラーメッセージを表示する */
if ($err_msg !== "") {
    print('<div>');
    print($err_msg);
    print('</div>');
}

?>
<header>
<h1>株式会社ペガサス</h1>

</header>

<div id="login_bar">
	<form action="log.php" method="POST">
       
		
			<p>ユーザーID</p>
			<input type="text" name="user_name" placeholder="ユーザーID">
		
		
			<p>パスワード</p>
			<input type="password" name="password" placeholder="パスワード">
		
<div id="button">
		<button type="submit">認証</button>
</div>
	</form>
</div>
</body>

</html>
