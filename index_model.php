<?php
    function now($date){
        date_default_timezone_set('Asia/Tokyo');
        return $date=date("Y/m/d H:i:s e P");
    }
    //特殊文字の変換
    function sql_entity($link){
        return mysqli_set_charset($link,'utf8');
    }
    function html_entity($name){
        return htmlspecialchars($name,ENT_QUOTES,'UTF-8');
    }
    //DBとの接続,切断
    function db_connect(){
        if(!$link=mysqli_connect(DB_HOST,DB_USER,DB_PASSWD,DB_NAME)){
           die('error:'.mysqli_connect_error()); 
        }
        mysqli_set_charset($link,DB_CHARACTER_SET);
        return $link;
    }
    function close_db_connect($link){
        mysqli_close($link);
    }
    function check_method(){
        return $_SERVER['REQUEST_METHOD'];
    }
    function get_post($name){
        if(isset($_POST[$name])===TRUE){
            return $_POST[$name];
        }else{
            return FALSE;
        }
    }     
    function upload_file($name,$path){
        if(is_uploaded_file($_FILES[$name]['tmp_name'])){
        $pic_name=substr(md5(uniqid()), 0, 10).($_FILES[$name]['name']); 
         move_uploaded_file($_FILES[$name]['tmp_name'],$path.$pic_name);
         return $pic_name;
        }
    }
    // //IPアドレスから国名判断
    // function country($ip_address){
    //     return $country = geoip_country_name_by_name($ip_address);
    // }
    function post_check($comment,$title,$url,$pic_name){            
        if($comment===""){
          return $error[]="*Enter please";
        }else if((str_word_count($title))>40){
            return $error[]="*write in 40 words or less";
        }else if(($url==="")&&($pic_name==="")){
            return $error[]="*You need to upload video or picture, whatever it's fine";
        }else if(($url!=="")&&($pic_name!=="")){
            return $error[]="*You cant upload both of video and picture";
        }
    }
    //投稿してもらった情報のINSERTの関数
    function insert($link,$user_id,$url,$pic_name,$title,$comment,$ip_address,$date){
        $comment=mysqli_real_escape_string ($link,$comment); 
        $query_insert='INSERT INTO onlyme(user_id,url,pic,title,comment,ip_address,date) 
                VALUES( \''.mysqli_real_escape_string($link,$user_id).'\'
                        ,\''.mysqli_real_escape_string($link,$url).'\'
                        ,\''.mysqli_real_escape_string($link,$pic_name).'\'
                        ,\''.mysqli_real_escape_string($link,$title).'\'
                        ,\''.mysqli_real_escape_string($link,$comment).'\'
                        ,\''.mysqli_real_escape_string($link,$ip_address).'\'
                        ,\''.mysqli_real_escape_string($link,$date).'\')';
        return insert_action($link,$query_insert);
    }
    function esc_sql($link,$name){
        return mysqli_real_escape_string($link,$name);
    }
    function insert_action($link,$query_insert){
        global $error_for_me;
        if(mysqli_query($link,$query_insert)!==TRUE){
            $error_for_me[]= "インザート ムリでした、すんません".$query_insert;
            return FALSE;
        }else{
            return TRUE;
        }
    }
    //me tooボタン押されんでも表示させたいけぇとりあえずSELECTの関数
    function select($link){
        $query_button='SELECT me_too_count 
                       FROM onlyme 
                       WHERE id='.$video_id.'';
        return  select_action($link,$query_button);              
    }
    function select_action($link,$query_button){
        global $error_for_me;
        if($push_button=mysqli_query($link,$query_button)){
            if($gift_button=mysqli_fetch_array($push_button)){
                 $me_too_count=intval($gift_button['me_too_count']);
            }
        }else{
            $error_for_me[]= "select countできなかったよ".$query_button;
        }
        
        return $me_too_count++;
    }
    //isset cookie関数
    function isset_cookie($video_id){
        global $error_for_me;
        if(isset($_COOKIE['me_too'.$video_id])===TRUE){
            return $error_for_me[]="投票済みっす".$video_id;
        }
    }
    //metooボタン誰が押したかをINSERTでDBに渡す  
    function who_clicked_insert($link,$user_id,$video_id){
        $query_insert='INSERT INTO me_too(user_id,id)
                            VALUES (\''.$user_id.'\',\''.$video_id.'\')';
        return insert_action($link,$query_insert);
    }
    function select_all_action($link,$query){ 
        global $error_for_me;
        if(($push=mysqli_query($link,$query))!==FALSE){ 
            while($gift=mysqli_fetch_assoc($push)){
                 $my_desk[]=$gift; 
            }return $my_desk;  
        }else{
            $error_for_me[]="select_allできんかった涙".$query;
            return FALSE;
        }
    }  
    //一行分だけとってくるselectの関数select
    function select_all_row($link,$query){
        global $error_for_me;
        if(($push=mysqli_query($link,$query))!==FALSE){ 
            $gift=mysqli_fetch_assoc($push); 
        }else{
            $error_for_me[]="select_all_rowできんかった涙";
            return FALSE;
        }return $gift;  
    }
    //コメントシェアのINSERT関数
    function insert_comment_share($link,$user_id,$me_too_comment,$video_id,$date){
        $query='INSERT INTO comment_share(user_id,comment,video_id,date) 
                      VALUES(\''.$user_id.'\',\''.$me_too_comment.'\',\''.$video_id.'\',\''.$date.'\')';
        return  insert_action($link,$query);
    }
    //postで何も送られてこなくても今までの全て表示させるためのSELECT関数
    function select_all($link){
        $query_all='SELECT onlyme.url,
                            onlyme.title,
                            onlyme.comment,
                            onlyme.id,
                            onlyme.pic,
                            onlyme.date,
                            count(me_too.user_id) AS count_me_too,
                            me_too.id AS me_too_id,
                            user.username,
                            user.country,
                            user.profile_photo,
                            user.age
                    FROM 
                            onlyme
                    LEFT JOIN
                            me_too
                    ON
                            onlyme.id=me_too.id
                    LEFT JOIN
                            user
                    ON
                            onlyme.user_id=user.user_id
                    GROUP BY
                            onlyme.id';
        return select_all_action($link,$query_all);               
    }
    // ajaxでmetooボタン押されたときに送るデータ
    function select_me_too($link,$id){
        $query_all='SELECT onlyme.url,
                            onlyme.title,
                            onlyme.comment,
                            onlyme.id,
                            onlyme.pic,
                            onlyme.date,
                            count(me_too.user_id) AS count_me_too,
                            me_too.id AS me_too_id,
                            user.username,
                            user.country,
                            user.profile_photo,
                            user.age
                    FROM 
                            onlyme
                    LEFT JOIN
                            me_too
                    ON
                            onlyme.id=me_too.id
                    LEFT JOIN
                            user
                    ON
                            onlyme.user_id=user.user_id
                    WHERE
                            onlyme.id='.$id;
        return select_all_action($link,$query_all);               
    }
    //postで何も送られてこなくてもsharecommentを表示させるためのSELECT関数
    function select_comment_share($link){
        $query='SELECT comment,video_id
                FROM comment_share';
        return action_comment_share($link,$query);                             
    }
    function action_comment_share($link,$query){
        global $error_for_me;
        if(($push=mysqli_query($link,$query))!==FALSE){
            while($gift=mysqli_fetch_assoc($push)){
                $desk_share_comment[$gift['video_id']][]=$gift['comment'];
            }return $desk_share_comment; 
        }else{
            $error_for_me[]="*ACTION_COMMENT_SHAREできんかった";
        }
    }
    //上のsharecomment誰が書いたか写真とかユーザーネームなど表示するためのSELECT関数
    function who_comment_share($link){
        $query='SELECT 
                        comment_share.comment,
                        comment_share.video_id,
                        user.age,
                        user.gender,
                        user.username,
                        user.country,
                        user.profile_photo
                FROM
                        user
                LEFT JOIN
                        comment_share
                ON      
                        comment_share.user_id=user.user_id';
        return action_who_comment_share($link,$query);        
    }
    //ajaxでコメントinsertしてselectでとってくるとこ
    function who_comment_share_ajax($link,$video_id){
        $query='SELECT 
                        comment_share.comment,
                        comment_share.video_id,
                        comment_share.date,
                        user.age,
                        user.gender,
                        user.username,
                        user.country,
                        user.profile_photo
                FROM
                        user
                LEFT JOIN
                        comment_share
                ON      
                        comment_share.user_id=user.user_id
                WHERE   
                        comment_share.video_id='.$video_id.'
                ORDER BY  
                        date DESC';
        return select_all_action($link,$query);        
    }
    function action_who_comment_share($link,$query){
        global $error_for_me;
        if(($push=mysqli_query($link,$query))!==FALSE){
            while($row=mysqli_fetch_assoc($push)){
                $data[$row['video_id']][]=array('comment'=>$row['comment'],'username'=>$row['username'],'profile_photo'=>$row['profile_photo'],'age'=>$row['age'],'country'=>$row['country'],'gender'=>$row['gender']);
            }
            return $data; 
        }else{
            $error_for_me[]="*ACTION_COMMENT_SHAREできんかった".$query;
        }
    }
    //postで何もおくられてこなくてもme_tooボタン誰が押したか表示するselect
    function who_clicked_select($link){
        $query='SELECT user.username,
                       user.profile_photo,
                       user.age,
                       user.country,
                       user.gender,
                       me_too.id
                FROM   
                       me_too
                LEFT JOIN
                       user
                ON
                        user.user_id=me_too.user_id';           
        return who_clicked_action($link,$query);                
    }
    function who_clicked_action($link,$query){
        global $error_for_me;
        if(($result=mysqli_query($link,$query))!==FALSE){
            while($row=mysqli_fetch_assoc($result)){
                $data[$row['id']][] = array('username'=>$row['username'],'profile_photo'=>$row['profile_photo'],'age'=>$row['age'],'country'=>$row['country'],'gender'=>$row['gender']);
            }
            return $data;
        }else{
            $error_for_me[]="select share部分できんかった涙".$query;
            return FALSE;
        }
    }
    //me_tooボタン押した記事の一覧（metooボタン押したかの確認に使う）
    function get_me_too_list($link,$user_id){
        $result=array();
        $query='SELECT id FROM me_too WHERE user_id='.$user_id.' GROUP BY id';
        $array=select_all_action($link,$query);
        foreach($array as $array_value){
            $result[]=$array_value['id'];
        }return $result;
    }
#####################  my video  ##################################### 
    //俺の動画誰がme_tooボタン押したか
    function my_video_insert($link,$user_id){
        $query='INSERT INTO my_video(user_id,date)
                            VALUES (\''.$user_id.'\',\''.$date.'\')';
        return insert_action($link,$query);                    
    }
    
    //下のselectにcount(my_video.user_id),と入れるとuser_idが一個しかとれない！！なぜ！？？？？？？？？？
    //俺の動画押した人の情報(who clickedでの表示)
    function info_my_video($link){
        $query='SELECT
                        my_video.user_id,
                        user.username,
                        user.profile_photo,
                        user.age,
                        user.country,
                        user.gender
                FROM
                        my_video
                LEFT JOIN
                        user
                ON
                        my_video.user_id=user.user_id';
        return select_all_action($link,$query);                
    }
    //俺の動画押した人のuser_idの配列、in_arrayでuser_id調べるために配列の形にする必要あり（you tooに変えるため）
    function my_video_select($link){
        $result=array();
        $query='SELECT 
                       user_id
                FROM 
                        my_video';
        $array=select_all_action($link,$query);
        foreach($array as $array_value){
            $result[]=$array_value['user_id'];
        }return $result;                
    }
    //俺の動画のmetoo押した人たち(count数えるため)
    function count_my_video($link){
        $query='SELECT count(user_id) as count FROM my_video';
        return select_all_action($link,$query);
    }
#######################################################################    
    //urlを埋め込み式のに変えてくれる関数
    function createvideotag($param){
        $param .= '<?php echo $url; ?>';
        //  youtube
        if(preg_match('#https?://www.youtube.com/.*#i',$param,$matches)){
            $parse_url = parse_url($param);
            $v_param = '<?php echo $url; ?>';
            //  動画IDを取得
            if (preg_match('#v=([-\w]{11})#i', $parse_url['query'], $v_matches)){
                $video_id = $v_matches[1];
                return '<iframe width="600px" height="350px" src="https://www.youtube.com/embed/' . $v_matches[1] .'" frameborder="0" allowfullscreen></iframe>';
            }
        }
    }
    //サムネ用の関数、上の関数の一番下のとこを変えただけ
    function createthumbnailtag($param){
        $param .= '<?php echo $url; ?>';
        //  youtube
        if(preg_match('#https?://www.youtube.com/.*#i',$param,$matches)){
            $parse_url = parse_url($param);
            $v_param = '<?php echo $url; ?>';
            //  動画IDを取得
            if (preg_match('#v=([-\w]{11})#i', $parse_url['query'], $v_matches)){
                $video_id = $v_matches[1];
                 return '<img src="http://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg" class="img-responsive pic_posted_img">';
            }
        }
    }
//######################################## SIGNUP LOGIN ####################################################################
    //signupされたときの関数
    function signup($link,$username,$mail,$age,$password,$password2,$gender,$profile_photo,$create_date){
        if(isset($_SESSION['user_id'])===TRUE){
            header('http://only-me.sakura.ne.jp/');  
        }else{
            if($username===""){
                $error[]="*please fill in username";
            }else if($mail===""){
                $error[]="please fill in mail address";
            }else if($age===""){
                $error[]="please fill in age";
            }else if($password===""){
                $error[]="please fill in password";
            }else if($password!==$password2){
                $error[]="please fill in same password";
            }else if($profile_photo===""){
                $error[]="please choose your profile photo";
            }else if($country===""){
                $error[]="please choose your country";
            }
            if(count($error)===0){
                $query_signup='INSERT INTO user(username,mail,age,password,gender,country,profile_photo,create_date)
                               VALUES(\''.$username.'\',\''.$mail.'\',\''.$age.'\',\''.$password.'\',\''.$gender.'\',\''.$country.'\',\''.$profile_photo.'\',\''.now($date).'\')';
                return signup_action($link,$query_signup);
                //ここでsession['user_id']に登録して、ホームページにいきたい。できるか？？selectでとってきて？？
            }
        }    
    }
    function signup_action($link,$query_signup){
        global $error_for_me;
        if(($query_button=mysqli_query($link,$query_signup))===FALSE){
            return FALSE;
            $error_for_me[]="signupINSERT失敗".$query_signup; 
        }else{
            return TRUE;
        }var_dump($error_for_me);
    }
    //loginされたときの関数
    function login($link,$username_or_mail,$password){
        if($username_or_mail===""){
            $error[]="please fill in username or mail address";
        }else if($password===""){
            $error[]="please fill in password";
        }
        if(count($error===0)){
            $query_login='SELECT user_id 
                          FROM user 
                          WHERE password=\''.$password.'\' AND mail=\''.$username_or_mail.'\' OR username=\''.$username_or_mail.'\'';
            return  select_row_action($link,$query_login);
        }else{
            $error[]="*failed login";
            return FALSE;
        }
    }
    function select_row_action($link,$query_login){
        global $error_for_me;
        if($login_button=mysqli_query($link,$query_login)){ 
            
            if(($login_gift=mysqli_fetch_assoc($login_button))!==FALSE){
                return $login_gift;
            }else{
                return FALSE;
            }
        }else{
            $error_for_me[]="select_row_action失敗".$query_login;
            return FALSE;
        }
    }
    
//######################################  MY PAGE  #####################################################
    //プロフィール写真表示させるためのselect
    function profile($link,$user_id){
        $query='SELECT profile_photo,username,age,gender,country
                FROM user
                WHERE user_id=\''.$user_id.'\'';
        return select_all_action($link,$query);        
    }
    //プロフィール写真の変更（UPDATE)
    function change_photo($link,$change_photo,$user_id){
        $query='UPDATE user
                SET profile_photo=\''.$change_photo.'\'
                WHERE user_id=\''.$user_id.'\'';
        return update($link,$query);        
    }
    //プロフィール写真変更
    function update($link,$query){
        global $error_for_me;
        if(mysqli_query($link,$query)!==TRUE){
            $error_for_me[]= "インザート ムリでした、すんません".$query;
            return FALSE;
        }else{
            return TRUE;
        }
    }
    //自分が投稿した写真、動画を表示
    function mypage_post($link,$user_id){
        $query_all='SELECT onlyme.url,
                            onlyme.title,
                            onlyme.comment,
                            onlyme.id,
                            onlyme.pic,
                            onlyme.date,
                            count(me_too.user_id) AS count_me_too,
                            me_too.id AS me_too_id
                    FROM 
                            onlyme
                    LEFT JOIN
                            me_too
                    ON
                            onlyme.id=me_too.id
                    LEFT JOIN
                            comment_share
                    ON
                            comment_share.user_id=onlyme.user_id
                    WHERE onlyme.user_id=\''.$user_id.'\' OR  me_too.user_id=\''.$user_id.'\' 
                                                          OR onlyme.id IN (SELECT comment_share.video_id
                                                                           FROM comment_share
                                                                           WHERE comment_share.user_id=\''.$user_id.'\')      
                    GROUP BY
                            onlyme.id';
        return select_all_action($link,$query_all);                             
    }
    //自分が投稿した物のコメント達
    function mypage_comment($link,$user_id){
        $query='SELECT 
                        video_id,comment
                FROM 
                        comment_share
                WHERE 
                        video_id in(
                        SELECT id 
                        FROM onlyme 
                        WHERE user_id=\''.$user_id.'\')';
        return action_mypage_comment($link,$query); 
    }
    function action_mypage_comment($link,$query){
        if(($result=mysqli_query($link,$query))!==FALSE){
            while($row=mysqli_fetch_array($result)){
                $data[$row['video_id']][] = $row['comment'];
            }
        }else{
            $error_for_me[] = "**MYPAGE_COMMENT部分できんかった涙".$query;
        }
    }
    //me_tooのカウント数
   function me_too_count($link){
        $query_all='SELECT 
                            count(me_too.user_id) AS count_me_too,
                            me_too.id AS me_too_id
                    FROM 
                            onlyme
                    LEFT JOIN
                            me_too
                    ON
                            onlyme.id=me_too.id
                    GROUP BY
                            onlyme.id';
        return select_all_action($link,$query_all);               
    }
    //誰がmetoo押したか一覧　さぶくえりー
    function mypage_who_clicked($link,$user_id){
        $query='SELECT
                        video_id,username
                FROM
                        user
                WHERE 
                        video_id in(
                        SELECT id 
                        FROM onlyme
                        WHERE user_id=\''.$user_id.'\')';
        return select_all_action($link,$query);                
    }
    ######################################  logout  ####################################
    function logout(){
         session_start();
        $session_name=session_name();   //セッション名取得、user_idってこと？？？
        $SESSION=array();
        if (isset($_COOKIE[$session_name])) {
           setcookie($session_name, '', time() - 42000);
        }
        session_destroy();
        header('https://only-me-cloned-kazoogon.c9users.io/signup_cont.php');
    }
    ###################################  メール送信  ####################################
    function mail_me_too($link,$id,$user_id){
        $query='SELECT
                        user.mail
                FROM
                        me_too
                JOIN 
                        onlyme ON me_too.id=onlyme.id
                JOIN
                        user ON onlyme.user_id=user.user_id
                WHERE   onlyme.id=\''.$id.'\'';
        $row=select_all_row($link,$query);
        $to=$row['mail']; //送り先のメールアドレス
        
        //誰がme_tooボタン押したか知らせるためのselect
        $query='SELECT
                        username
                FROM
                        user
                WHERE
                        user_id=\''.$user_id.'\'';
        $row=select_all_row($link,$query); //誰が押したか 
        $body=$row['username'].' pushed me too button of your article! http://only-me.sakura.ne.jp/';
        send_mail($to,'someone pushed me too button!',$body,'Is it just me');
    }
    function mail_comment($link,$id,$user_id){
        $query='SELECT
                        user.mail
                FROM
                        me_too
                JOIN 
                        onlyme ON me_too.id=onlyme.id
                JOIN
                        user ON onlyme.user_id=user.user_id
                WHERE   onlyme.id=\''.$id.'\'';
        $row=select_all_row($link,$query);
        $to=$row['mail']; //送り先のメールアドレス
        
        //誰がコメント書いたか知らせるためのselect
        $query='SELECT
                        username
                FROM
                        user
                WHERE
                        user_id=\''.$user_id.'\'';
        $row=select_all_row($link,$query); //誰が押したか 
        $body=$row['username'].' commented of your article! http://only-me.sakura.ne.jp/';
        send_mail($to,'someone commented on your article!',$body,'Is it just me');
    }
    