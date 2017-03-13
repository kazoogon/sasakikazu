<?php 
require_once ('index_model.php');
require_once ('define.php');
require_once ('mail_test.php');
/* 後ほど切り替えてテストする */
$lang = "ja_JP.UTF-8";
//$lang = "en_US.UTF-8";

// /* 翻訳ファイル名 */
// $domain = "messages";

// setlocale(LC_ALL, $lang);

// /* 翻訳ファイルの置き場を指定 */
// bindtextdomain($domain, "./locale/");

// /* 翻訳ファイル名の指定 */
// textdomain($domain);

    $video= "";
    $comment= "";
    $error= array();
    $error_for_me=array();
    
    $url="";
    $pic_name="";
    
    $my_desk=array();
    $my_desk_share_second=array();
    
    // now($date);
    date_default_timezone_set('Asia/Tokyo');
    $date=date("Y/m/d H:i:s");
    //セッション変数からuser_id取得（loginしたことあるかの確認)
    session_start();
    if (isset($_SESSION['user_id']) === TRUE) {
       $user_id = $_SESSION['user_id'];
    } else {
       // 非ログインの場合、ログインページへリダイレクト
       //header('Location: https://only-me-cloned-kazoogon.c9users.io/signup_cont.php');
        header('Location: http://only-me.sakura.ne.jp/signup_cont.php');
       exit;
    }
    
    $link= db_connect();
        sql_entity($link);
        //投稿してもらったのとりあえずinsert
        if(check_method()==="POST"){
            $hide=get_post('hide');
            if($hide==="post"){ 
                $url=get_post('url');
                $comment=get_post('comment');
                $title=get_post('title');
                $ip_address=$_SERVER['REMOTE_ADDR'];
                $pic_name=upload_file('pic','./onlyme_pic/');
                post_check($comment,$title,$url,$pic_name);
                if(insert($link,$user_id,$url,$pic_name,$title,$comment,$ip_address,$date)===FALSE){
                    $error[]="書き込みできませんでした";
                }
            }        
            // // metoo button押したら数が増えるINSERT
            if($hide==="me_too_count"){
                 $video_id=get_post('video_id'); //var_dump($video_id);
                if(who_clicked_insert($link,$user_id,$video_id)===FALSE){
                    $error_for_me[]="**ME_TOO_BUTTON_INSERTできず";
                }
                //メール送信
                if(mail_me_too($link,$video_id,$user_id)===FALSE){
                    $error_for_me[]="**mail送信できませんでした";
                }
            }
            $hide=get_post('hide');
                if($hide==="me_too_comment"){
                    $video_id=get_post('video_id'); var_dump($video_id);
                    $me_too_comment=get_post('me_too_comment');
                    if(insert_comment_share($link,$user_id,$me_too_comment,$video_id)!==TRUE){
                        $error_for_me[]="metoo comment INSERTできなかった".$insert_comment_share;
                    }
                }    
            
        }
        //postで何もこなくてもすべて表示させるためのSELECT
        if(($select_all=select_all($link))===FALSE){
            $error_for_me[]="select_all失敗";
        }
        //postで何もこなくてもシェアコメントを表示させるためのselect(tableが違うのでこれが必要)       
        if(($select_comment_share=select_comment_share($link))===FALSE){
            $error_for_me[]="select_comment_share失敗";
        }
        //上のシェアコメント誰が書いたか表示させるためのselect
        if(($who_comment_share=who_comment_share($link))===FALSE){
            $error_for_me[]="who_comment_share失敗";
        }
        //postで何もこなくてもme_tooボタン誰が押したか表示させるselect
        if(($who_clicked_select=who_clicked_select($link))===FALSE){
            $error_for_me[]="*WHO_CLICKED_SELECT失敗";
        }
        //me_tooボタン押してるかリストの取得（you tooに変えるため）
        if(($me_too_list=get_me_too_list($link,$user_id))===FALSE){
            $error_for_me[]="*get_me_too_list失敗";
        }    
//################  my video  ###################        
        //俺の動画のmetoo押した人たちのcoount
        if(($count_my_video=count_my_video($link))===FALSE){
            $error_for_me[]="*chcek_my_video失敗";
        }
        //me tooボタン合計数と誰が押したかの取得
        if(($my_video_select=my_video_select($link))===FALSE){
            $error_for_me[]="ajax metoo　select失敗";
        }
        //metooボタン押した人の情報（who clicked?で表示させるため）
        if(($info_my_video=info_my_video($link))===FALSE){
            $error_for_me[]="**info_my_video失敗";
        }
    close_db_connect($link);
    //urlを埋め込み式のに変えてくれる関数
    createvideotag($param);
    //サムネ用の関数、上の関数の一番下のとこを変えただけ
    createthumbnailtag($param);

//   var_dump($error_for_me);
//   echo "<pre>"; var_dump($); echo "</pre>";
include_once ('index_view.php');