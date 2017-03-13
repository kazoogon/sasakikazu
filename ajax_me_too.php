<?php 
header("Content-Type: application/json; charset=utf-8");
require_once('mail_test.php');
$user_id=$_POST['user_id'];
$video_id=$_POST['video_id'];
// $user_id=14;
// $video_id=104;  
// こうやって実際に値を入れてみてテストする
require_once('index_model.php');
require_once('define.php');
$link=db_connect();
                    
 //metooボタン押したときにデータアップデートをINSERTでDBに渡す  
    if(who_clicked_insert($link,$user_id,$video_id)===FALSE){
        $error_for_me[]="ajax metooボタンアップロード失敗";
    }
    //me tooボタン合計数の取得
    if(($result=select_me_too($link,$video_id))===FALSE){
        $error_for_me[]="ajax metoo　select失敗";
    }
    //メールの送信
    if(mail_me_too($link,$video_id,$user_id)===FALSE){
        $error_for_me[]="**mail送信できませんでした";
    }
    $count=$result[0]['count_me_too']; //ここおっけ？？？？
    $array_count=array('count'=>$count);
    echo json_encode($array_count); 
    

