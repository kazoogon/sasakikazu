<?php
session_start();
$session_name = session_name(); //sessionにあるクッキーを保管する箱の名前を読み取る

$_SESSION = array();

if (isset($_COOKIE[$session_name])) {
   setcookie($session_name, '', time() - 42000);      //ユーザーのcookieに保存されとるsessionID削除
}
// セッションIDを無効化
session_destroy();
//header('Location: https://only-me-cloned-kazoogon.c9users.io/signup_cont.php');
 header('http://only-me.sakura.ne.jp/');