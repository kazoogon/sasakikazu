<!DOCTYPE html>
<html>
<head>
    <title>mypage</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.css" type="text/css" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../common.css">
    <link rel="stylesheet" type="text/css" href="mypage.css"> 
    <!--このページ特有のcssが上書きできるように後に書く-->
    <style>
   .profile{
       position:fixed;
   }
    </style>
    <script src="//code.jquery.com/jquery-latest.js"></script>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

<!--sweetalertのcdn-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!--bootstrap cdn-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container-fluid">
        <nav class="row">
            <ul class="header_nav">
                <li class="col-md-3 col-sm-12 col-xs-12"><a href="https://only-me-cloned-kazoogon.c9users.io"><span class="glyphicon glyphicon-home"></span>Home</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a class="about_onlyme" href="#" data-featherlight="#about"><span class="glyphicon glyphicon-eye-open"></span>About this site</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a href="#" class="post_your_onlyme" data-featherlight="#post"><span class="glyphicon glyphicon-share-alt"></span>Post!</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a href="https://only-me-cloned-kazoogon.c9users.io/../logout.php">Log out</a></li>
            </ul>
        </nav>
    </div>
    <!--about this siteクリックで出てくるfeather-light-->
    <div id="about" class="feather-light">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/cLFFLqL1vgI" frameborder="0" allowfullscreen></iframe><br>
        <div class="explain">
            I(the developer of this site) can't stop doing it!  Is it just me?? If you think "Me too!", click the "Me too" button!<br>
            If you have anything that makes you think "Is It Just Me?" just post it, maybe it's not only you!<br>
        </div>
        <!--me too ボタン-->
        <div class="buttons">
            <?php if(in_array($value['id'],$me_too_list,TRUE)===TRUE){ ?>
                <button class="you_too" disabled>you too!<?php echo $value['count_me_too']; ?></button>
            <?php }else{ ?>    
                <button class="me_too" value="<?php echo $value['id'] ?>">me too!<?php echo $value['count_me_too']; ?></button>
            <?php } ?>    
            <a class="who_clicked" href="#" data-featherlight="#who_clicked<?php echo $key; ?>">☚<input type="submit" name="who_clicked"  value="who clicked?"></a>
            <input type="hidden" name="hide" value="who_clicked">
        </div>    
    </div>
    <!--POST!で出てくるfeather-light-->
    <div id="post" class="feather-light">
        <form action="index.php" method="POST" name="only_me_form" id="only_me_form" enctype="multipart/form-data">
        <!--vid pic差し込み部分-->
            <input type="text" name="url" class="text" size=90 placeholder="URL of video(youtube)">
            <input type="file" name="pic" class="pic">
            <div class="at_least_one">*you need to post picture or video at least one</div>
        
        <!--タイトルと内容書き込み部分、エラー表示あり-->
            <input type="text" name="title" class="title" size=90 placeholder="Title of your&quot;Is It Just Me?!&quot;" >
            <textarea name="comment" class="textarea" cols="45" placeholder="Write it down here, your&quot;Is It Just me?!&quot;"></textarea>

        <!--送信ボタン-->
            <input type="hidden" name="hide" value="post">
            <p class="button_box"><input type="submit" name="post" class="button" value="POST!"></p>  
            <!--真ん中にするためのpタグ-->

        </form>
    </div>
    <div class="main">
        <?php foreach($profile as $value_profile) { ?>
            <div class="profile">
                <img class="profile_photo" src="../onlyme_pic/<?php echo $value_profile['profile_photo']; ?>">
                <form action="./mypage_cont.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="change_photo">
                    <div>
                        <input type="submit" name="change_photo" value="change profile photo">
                        <input type="hidden" name="hide" value="change_photo">
                    </div>
                </form>
                <p class="username"><?php echo htmlspecialchars($value_profile['username'],ENT_QUOTES,'UTF-8'); ?></p>
                <div class="age">age:<?php echo htmlspecialchars($value_profile['age'],ENT_QUOTES,'UTF-8') ?></div>
                <div class="gender">gender:<?php echo htmlspecialchars($value_profile['gender'],ENT_QUOTES,'UTF-8'); ?></div>
                <div class="country">country:<?php echo htmlspecialchars($value_profile['country'],ENT_QUOTES,'UTF-8') ?></div>
            </div>
        <?php } ?>
        <?php foreach(array_reverse($mypage_post) as $key=>$value_mypage_post) { ?>
            <!--自分が投稿した,metoo押した,comment書いた記事を表示-->
            <div class="post">
                    <div class="title_mypage"><?php echo $value_mypage_post['title']; ?></div>
                    <?php if(($value_mypage_post['pic'])!==""){ ?>
                        <img class="pic_vid" src="./onlyme_pic/<?php echo htmlspecialchars($value_mypage_post['pic'],ENT_QUOTES,'UTF-8'); ?>">
                    <?php } ?>
                    <?php if(($value_mypage_post['url'])!==""){ ?> 
                        <a class="pic_vid"><?php echo createthumbnailtag($value_mypage_post['url']); ?></a>
                    <?php } ?>
                    <?php echo htmlspecialchars($value_profile['comment'],ENT_QUOTES,'UTF-8'); ?>
                    <div class="buttons">
                        <?php if(in_array($value_my_page_post['id'],$me_too_list,TRUE)===TRUE){ ?>
                            <button class="you_too" disabled>you too!<?php echo $value_mypage_post['count_me_too']; ?></button>
                        <?php }else{ ?>    
                            <button class="me_too" value="<?php echo $value_my_page_post['id'] ?>">me too!<?php echo $value_mypage_post['count_me_too']; ?></button>
                        <?php } ?>    
                        <a class="who_clicked" href="#" data-featherlight="#who_clicked<?php echo $key; ?>">☚<input type="submit" name="who_clicked"  value="who clicked?"></a>
                        <input type="hidden" name="hide" value="who_clicked">
                    </div>    
                <!--誰がコメントしたかの写真、名前、コメントなどの表示-->
                <?php   if(isset($who_comment_share[$value_mypage_post['id']])===TRUE) { ?>
                    <?php   foreach($who_comment_share[$value_mypage_post['id']] as $value_who_comment_share) { ?>
                        <div class="share_comment">
                            <span class="who_clicked_profile_photo"><img src="../onlyme_pic/<?php echo htmlspecialchars($value_comment_share['profile_photo'],ENT_QUOTES,'UTF-8'); ?>"></span>
                            <?php if($value_who_comment_share['gender']==="male") { ?>
                                  <span class="male"><?php echo htmlspecialchars($value_comment_share['username'],ENT_QUOTES,'UTF-8'); ?></span>
                            <?php } ?>
                            <?php if($value_who_comment_share['gender']==="female") { ?>
                                  <span class="female"><?php echo htmlspecialchars($value_comment_share['username'],ENT_QUOTES,'UTF-8'); ?></span>
                            <?php } ?>
                            <?php echo htmlspecialchars($value_comment_share['age'],ENT_QUOTES,'UTF-8'); ?>
                            <?php echo htmlspecialchars($value_comment_share['country'],ENT_QUOTES,'UTF-8'); ?>:
                            <?php echo htmlspecialchars($value_comment_share['comment'],ENT_QUOTES,'UTF-8'); ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <!--#### WHO CLICKED?押したら出てくるfeatherlight!! ###-->
            <div id="who_clicked<?php echo $key; ?>" class="feather-light">
                <?php foreach($who_clicked_select[$value_mypage_post['id']] as $value_clicked) { ?>
                    <p class="who_clicked_profile_photo">
                        <img src="../onlyme_pic/<?php echo htmlspecialchars($value_clicked['profile_photo'],ENT_QUOTES,'UTF-8'); ?>">
                        <?php if($value_clicked['gender']==="male") { ?>
                              <span class="male"><?php echo htmlspecialchars($value_clicked['username'],ENT_QUOTES,'UTF-8'); ?></span>
                        <?php } ?>
                        <?php if($value_clicked['gender']==="female") { ?>
                              <span class="female"><?php echo htmlspecialchars($value_clicked['username'],ENT_QUOTES,'UTF-8'); ?></span>
                        <?php } ?>
                        <?php echo htmlspecialchars($value_clicked['age'],ENT_QUOTES,'UTF-8'); ?>
                        <?php echo htmlspecialchars($value_clicked['country'],ENT_QUOTES,'UTF-8'); ?>
                    </p>
                <?php } ?>
            </div>    
        <?php } ?>
        <!--自分がmetooボタン押した記事を表示-->
        <!--<?php foreach($article_push_me_too as $value_article_push_me_too) { ?>-->
        <!--    <div class="post">-->
        <!--        <div class="title_mypage">-->
        <!--            <?php echo $value_article_push_me_too['title']; ?>-->
        <!--        </div>-->
        <!--            <?php if(($value_article_push_me_too['pic'])!==""){ ?>-->
        <!--                <img class="pic_vid" src="../onlyme_pic/<?php echo $value_article_push_me_too['pic']; ?>">-->
        <!--            <?php } ?>-->
        <!--            <?php if(($value_article_push_me_too['url'])!==""){ ?> -->
        <!--                <a class="pic_vid"><?php echo createthumbnailtag($value_article_push_me_too['url']); ?></a>-->
        <!--            <?php } ?>-->
        <!--            <?php echo $value_article_push_me_too['comment']; ?>-->
        <!--    </div>-->
        <!--<?php } ?>-->
        <!--自分がコメントした記事を表示-->
        <!--<?php foreach($article_write_comment as $value_article_write_comment) { ?>-->
        <!--    <div class="post">-->
        <!--        <div class="title_mypage">-->
        <!--            <?php echo $value_article_write_comment['title']; ?>-->
        <!--        </div>-->
        <!--        <?php if(($value_article_write_comment['pic'])!==""){ ?>-->
        <!--            <img class="pic_vid" src="../onlyme_pic/<?php echo $value_article_write_comment['pic']; ?>">-->
        <!--        <?php } ?>-->
        <!--        <?php if(($value_article_write_comment['url'])!==""){ ?> -->
        <!--            <a class="pic_vid"><?php echo createthumbnailtag($value_article_write_comment['url']); ?></a>-->
        <!--        <?php } ?>-->
        <!--        <?php echo $value_article_write_comment['comment']; ?>-->
        <!--    </div>-->
        <!--<?php } ?>-->
    </div>
    
<script>
//me_tooボタン押したら起こる挙動(数字が増えて色が変わってyou too!っていう文字に変わる)
$(function(){
    $('.me_too').click(function(){ 
        $container=$(this);
        $.ajax({
            type: "POST",
            url: "../ajax_me_too.php",
            data: {
                video_id : $(this).val(),
                user_id : <?php echo $user_id; ?>
            },  
            success: function(json)
                {  
                    $container.removeClass('me_too').addClass('you_too').html('you too!'+json['count']).prop("disabled", true); 
                },
            error: function(XMLHttpRequest,textStatus,errorThrown)
                {
                    alert('エラーです');
                }
        }); 
        return false;
    });
    $('.share_button').click(function(){ 
        $container=$(this);
        $.ajax({
            type : "POST",
            url : "../ajax_comment.php",
            data : {
                video_id : $(this).val(),
                comment : $(this).prev().val(), //お兄さん（コードの上）下の場合はnext
                user_id : <?php echo $user_id; ?>
            },
            success: function(json)
            {   $container.next().html(""); //ここで消さないと今までのコメントが二重になって表示されて俺がコメントしたのがでてきてしまう
                for(var i=0; i<json.length; i++){
                     $container.next().append('<img src="../onlyme_pic/'+json[i].profile_photo+'">');
                    $container.next().append(json[i].username);
                    $container.next().append(json[i].age);
                    $container.next().append(json[i].country);
                    $container.next().append(json[i].comment);
                }
            },
            error : function(XMLHttpRequest,textStatus,errorThrown)
            {
                alert ('ajax comment failed!!');
            }
        });
    });
});
// ほんまに投稿するかの確認画面
    /*global $*/
    /*global swal*/
        $(function(){
            $('.button').click(function(e){
                e.preventDefault();
                    swal({
                        title: "Post it,ok?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        confirmButtonText: 'Yes, I am sure!',
                        cancelButtonText: "No, cancel it!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm){
                            $('.featherlight #only_me_form').submit();
                        } else {
                          swal("Cancelled", "Your imaginary file is safe :)", "error");
                        }
                     });
            });
        });
        //ハンバーガーメニュースクロールしても固定
         $(window).scroll(function() {
            if ($(window).scrollTop() > 350) {
                $('#header').addClass('fixed');
            } else {
                $('#header').removeClass('fixed');
            }
        });
        //ハンバーガーメニュークリック
        $('.toggle').click(function(){
            $('#header').toggleClass('open');
        });
</script>
    
</body>    
</html>