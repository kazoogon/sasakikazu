<?php
require_once '../index_model.php';
require_once '../define.php';

session_start();
    if (isset($_SESSION['user_id']) === TRUE) {
       $user_id = $_SESSION['user_id'];
    } else {
       // 非ログインの場合、ログインページへリダイレクト
       header('Location: https://only-me-cloned-kazoogon.c9users.io/signup_cont.php');
       exit;
    }
$link= db_connect();
    sql_entity($link);
//profile写真提示    
if(($profile=profile($link,$user_id))===FALSE){
    $error_for_me[]="*PROFILE写真selectできず";
}
##########  記事の表示 ##############
//自分が投稿した写真、記事を表示
if(($mypage_post=mypage_post($link,$user_id))===FALSE){   //ここにissetでできてたら実行のコード　title,pic,id,me_too_id,count_me_too
    $error_for_me[]="mypag_postできませんでした";
}
// //metoo押した記事を表示
// if(($article_push_me_too=article_push_me_too($link,$user_id))===FALSE){
//     $error_for_me[]="article_pushed_me_tooできませんでした".$query;
// }
// //comment書いた記事を表示
// if(($article_write_comment=article_write_comment($link,$user_id))===FALSE){
//     $error_for_me[]="article_write_commentできませんでした".$query;
// }
// // 上３つを新しい順に表示
// $array_merge=array_merge($mypage_post,$article_push_me_too,$article_write_comment);
// function user_sort($a,$b){
//     if($a['date']>$b['date']){
//         return -1; 
//     }else if($a['date']<$b['date']){
//         return 1;
//     }else{
//         return 0;
//     }
// }
// usort($array_merge,'user_sort');
#####################################

//コメント誰が書いたかの写真と名前の表示一覧
if(($who_comment_share=who_comment_share($link,$user_id))===FALSE){
    $error_for_me[]="who_comment_shareできませんでした".$query;
}
// //metooのカウント数
// if(($mypage_count=mypage_count($link,$video_id))===FALSE){
//     $error_for_me[]="mypage_countできませんでした";
// }
//誰がmetooボタン押したかの表示
if(($who_clicked_select=who_clicked_select($link))===FALSE){
    $error_for_me[]="*WHO_CLICKED_SELECT失敗";
}
if(($select_all=select_all($link))===FALSE){
    $error_for_me[]="select_allできませんでした";
}
//me_tooボタン押してるかリストの取得（you tooに変えるため）
if(($me_too_list=get_me_too_list($link,$user_id))===FALSE){
    $error_for_me[]="*get_me_too_list失敗";
}
if(check_method()==="POST"){
    $hide=get_post('hide');
    if($hide==="logout"){
        logout($session_name);
    }
    if($hide==="change_photo"){ var_dump("TEST");
        $change_photo=upload_file('change_photo','../onlyme_pic/');
        change_photo($link,$change_photo,$user_id);
    }
}
//  echo "<pre>"; var_dump($array_merge); echo "</pre>";
//   echo "<pre>"; var_dump($article_pushed_me_too); echo "</pre>";
//  var_dump($error_for_me);
include_once 'mypage_view.php';