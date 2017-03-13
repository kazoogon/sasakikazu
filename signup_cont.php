<?php
require_once 'index_model.php';
require_once 'define.php';

$link= db_connect();
    sql_entity($link);
//sign up部分
    $hide=get_post('hide');
        if($hide==="signup"){
            $username=get_post('username');
            $mail=get_post('mail');
            $age=get_post('age');
            $password=get_post('password');
            $password2=get_post('password2');
            $country=get_post('country');
            $gender=get_post('gender');
            $profile_photo=upload_file('profile_photo','./onlyme_pic/');
            $create_date=get_post('create_date');
            
            if((signup($link,$username,$mail,$age,$password,$password2,$country,$gender,$profile_photo,$create_date))===FALSE){
                $error_for_me[]="signup INSERT失敗";
            }
        }
//login部分
    $hide=get_post('hide');
        if($hide==="login"){
            $username_or_mail=get_post('username_or_mail');
            $password=get_post('password');
            $checkbox=get_post('remember');
            
            if(($login=login($link,$username_or_mail,$password))===FALSE){
                $error_for_me[]="login SELECT失敗";
            }else{
                if($checkbox==="checked"){
                    setcookie('username_or_mail', $mail, time() + 60 * 60 * 24 * 365);
                }    
                    // setcookie('password', $password, time() + 60 * 60 * 24 * 365);   パスワードはcookieで送ったらだめよ!!!!!!
                    session_start();
                    $_SESSION['user_id']=$login['user_id'];
                     header('http://only-me.sakura.ne.jp/');
                    //header('Location: https://only-me-cloned-kazoogon.c9users.io');
                    exit;
            }
        }
close_db_connect($link);
include_once 'signup_view.php';