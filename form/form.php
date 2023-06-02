<?php

# 送信先アドレス
$mailto = "xxx@internetacademy.co.jp";
# 送信後画面からの戻り先
$toppage = "./form.html";

#===========================================================
#  入力情報の受け取りと加工
#===========================================================
$name = $_POST["name"];
$email = $_POST["email"];
$comment = $_POST["comment"];

# 無効化
$name  = htmlentities($name,ENT_QUOTES, "UTF-8");
$email = htmlentities($email,ENT_QUOTES, "UTF-8");
$comment = htmlentities($comment,ENT_QUOTES, "UTF-8");

# 改行処理
$name = str_replace("\r\n", "", $name); //名前の中に改行があったら削除
$email = str_replace("\r\n", "", $email); //メールアドレスの中に改行があったら削除
$comment = str_replace("\r\n", "\t", $comment); 
$comment = str_replace("\r", "\t", $comment);
$comment = str_replace("\n", "\t", $comment);

# 入力チェック
if ($name == "") { error("名前が未入力です"); }
if (!preg_match("/\w+@\w+/",$email)){ error("メールアドレスが不正です"); }
if ($comment == "") { error("コメントが未入力です"); }

# 分岐チェック
if ($_POST["mode"] == "post") { conf_form(); }
else if($_POST["mode"] == "send") { send_form(); }

#-----------------------------------------------------------
#  確認画面
#-----------------------------------------------------------
function conf_form(){
  global $name;
  global $email;
  global $comment;

  # テンプレート読み込み
  $conf = fopen("tmpl/conf.tmpl","r") or die;
  $size = filesize("tmpl/conf.tmpl");
  $data = fread($conf , $size);
  fclose($conf);

  # 文字置き換え
  $data = str_replace("!name!", $name, $data);
  $data = str_replace("!email!", $email, $data);
  $data = str_replace("!comment!", $comment, $data);

   # 表示
   echo $data;
   exit;
}

#-----------------------------------------------------------
#  エラー画面
#-----------------------------------------------------------
function error($msg){
  $error = fopen("tmpl/error.tmpl","r");
  $size = filesize("tmpl/error.tmpl");
  $data =  fread($error , $size);
  fclose($error);

  #文字置き換え
  $data = str_replace("!message!", $msg, $data);

  #表示
  echo $data;
  exit;
}

#-----------------------------------------------------------
#  CSV書込
#-----------------------------------------------------------
function send_form(){
  global $name;
  global $email;
  global $comment;

  $user_input = array($name,$email,$comment);
  mb_convert_variables("SJIS","UTF-8",$user_input);
  $fh = fopen("user.csv","a");
  flock($fh,LOCK_EX);
  fputcsv($fh, $user_input);
  flock($fh,LOCK_UN);
  fclose($fh);

  # テンプレート読み込み
  $conf = fopen("tmpl/send.tmpl","r") or die;
  $size = filesize("tmpl/send.tmpl");
  $data = fread($conf , $size);
  fclose($conf);

  #文字置き換え
  global $toppage;
  $data = str_replace("!top!", $toppage, $data);
  #表示
  echo $data;
  exit;
}
