<?php 
header("Content-Type: application/json; charset=utf-8");
$user_id=$_POST['user_id'];
require_once('index_model.php');
require_once('define.php');


$link=db_connect();
                    
 //俺のビデオにmetooボタン押したときにデータアップデートをINSERTでDBに渡す  
if(my_video_insert($link,$user_id,$date)===FALSE){
    $error_for_me[]="ajax俺のビデオ metooボタンアップロード失敗";
}
//俺の動画のme_tooボタンの数の情報を取ってくる
if(($count_my_video=count_my_video($link))===FALSE){
    $error_for_me[]="my_video_select失敗";
}

    $count=$count_my_video[0]['count']; //ここおっけ？？？？
    $array_count=array('count'=>$count);
    echo json_encode($array_count); 
 