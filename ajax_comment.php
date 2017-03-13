<?php
header("Content-Type: application/json; charset=utf-8");
require_once('mail_test.php');
$user_id=$_POST['user_id'];
$me_too_comment=$_POST['comment'];
$video_id=$_POST['video_id'];
date_default_timezone_set('Asia/Tokyo');
$date=date("Y/m/d H:i:s");

// $user_id=14;
// $video_id=104;
//  $me_too_comment='test test!!';
require_once('index_model.php');
require_once('define.php');
$link=db_connect();
//commentシェアされたときinsertで渡して,selectでそこのvideoidのコメント全部取ってくる
    if(insert_comment_share($link,$user_id,$me_too_comment,$video_id,$date)===FALSE){
        $error_for_me[]="ajax comment share 失敗";
    }
    //コメントとusername,profile_photoなどをselectでとってくる
    if(($who_comment_share_ajax=who_comment_share_ajax($link,$video_id))===FALSE){
        $error_for_me[]="ajax comment share SELET 失敗";
    }
    //メールの送信
    if(mail_comment($link,$video_id,$user_id)===FALSE){
        $error_for_me[]="**mail送信できませんでした";
    }
// var_dump($who_comment_share_ajax);
echo json_encode($who_comment_share_ajax);