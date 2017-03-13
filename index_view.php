<!DOCTYPE html>
<html>
    <head>
        <title>Is it just me?</title>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <!--ランダムに背景色出そうと挑戦したやつ<script type="https://cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js"></script>-->
        <!--<link rel="stylesheet" href="//u-zoroy.com/sozai/css/style.css" type="text/css">  <!--マウス乗せたらもじでてくるやつ-->
        <!--<script type="text/javascript" src="//u-zoroy.com/sozai/js/common.js"></script>   <!--マウス乗せたらもじでてくるやつ-->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
         <link href="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.css" type="text/css" rel="stylesheet" />
         <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
         <meta name="viewport" content="width=device-width,initial-scale=1">
         <link rel="stylesheet" type="text/css" href="common.css"　media="screen">
         <link rel="stylesheet" type="text/css" href="onlyme.css" media="screen">
    <style>
        .me_too_count{
            float:left;
        }
    </style>  
    </head>
<body>
<!--<div class="container-fluid">bootstrapでのdiv-->
    <header id="header">
        <div class="header_bg">
            <div class="toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </div>
        </div>
        <nav class="nav">
            <ul>
                <li class="col-md-3 col-sm-12 col-xs-12"><a class="mypage" href="https://only-me-cloned-kazoogon.c9users.io/mypage/mypage_cont.php"><span class="glyphicon glyphicon-user"></span>My Page</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a class="about" href="#" data-featherlight="#about"><span class="glyphicon glyphicon-eye-open"></span>About this site</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a class="post" href="#" data-featherlight="#post"><span class="glyphicon glyphicon-share-alt"></span>Post!</a></li>
                <li class="col-md-3 col-sm-12 col-xs-12"><a class="logout" href="https://only-me-cloned-kazoogon.c9users.io/../logout.php">Log out</a></li>
            </ul>
        </nav>
    </header>    
    <div class="main-title">
        <h1><?php print _('Is It Just Me?') ?></h1>
        <h2>Maybe not.Search for others around the world who agree!</h2>
    </div>
<!--</div>    -->
<div class="container-fluid">
    <!--errorがでてくるとこ-->
    <?php if(count($error)!==0){ ?>
            <?php foreach($error as $value){ ?>
            <div class="error"><?php echo $value; ?></div>
            <?php } ?>
    <?php } ?>  
    
<!--featherlightで出てきて投稿してもらうABOUT IS IT YOURS画面-->                           
        <div id="about" class="feather-light">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/cLFFLqL1vgI" frameborder="0" allowfullscreen></iframe><br>
            <div class="explain">
                I(the developer of this site) can't stop doing it!  Is it just me??<br>
                If you think "Me too!", click the "Me too" button!<br>
                If you have anything that makes you think "Is It Just Me?" just post it, maybe it's not only you!<br>
            </div>
            <!--me too who clickedボタン-->
            <div class="buttons">
                    
                    <?php foreach($count_my_video as $value_count_my_video){ ?>
                        <?php if(in_array($user_id,$my_video_select,TRUE)===TRUE){ ?>
                            <button class="you_too" disabled>you too!<?php echo $value_count_my_video['count']; ?></button>
                        <?php }else{ ?>    
                            <button class="me_too_my_video" value="">me too!<?php echo $value_count_my_video['count']; ?></button>
                        <?php } ?> 
                    <?php } ?>
                    <a class="who_clicked" href="#" data-featherlight="#my_video_who_clicked">☚<input type="submit" name="who_clicked"  value="who clicked?"></a>
                    <input type="hidden" name="hide" value="who_clicked">
            </div>    
        </div>
<!--俺の動画のwho clicked?のfeather right-->
        <div id="my_video_who_clicked" class="feather-light">
            <?php if(isset($count_my_video)===TRUE) { ?>
                <?php foreach($info_my_video as $value_info_my_video) { ?>
                    <p class="who_clicked_profile_photo">
                        <img src="../onlyme_pic/<?php echo $value_info_my_video['profile_photo'] ?>">
                        <?php if($value_info_my_video['gender']==="male") { ?>
                              <span class="male"><?php echo $value_info_my_video['username']; ?></span>
                        <?php } ?>
                        <?php if($value_info_my_video['gender']==="female") { ?>
                              <span class="female"><?php echo $value_info_my_video['username']; ?></span>
                        <?php } ?>
                        <?php echo $value_info_my_video['age']; ?>
                        <?php echo $value_info_my_video['country']; ?>
                    </p>
                    <!--htmlspecialchars($value_clicked,ENT_QUOTES,'UTF-8')-->
                <?php } ?>
            <?php } ?>    
        </div>
<!--POSTのfeatherlight-->
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
    
    <!--投稿してもらったのを表示する部分-->
    <div class="main">
        <?php foreach(array_reverse($select_all) as $key=>$value){ ?>    
                <div class="display col-md-4 col-sm-12 col-xs-12"> 
                <!--<p><?php print $key; ?></p>-->
                    <td>
                        <a href="#" data-featherlight="#display<?php echo $key; ?>">
                        <div class="little_profile">
                            <span class="who_clicked_profile_photo"><img src="../onlyme_pic/<?php echo htmlspecialchars($value['profile_photo'],ENT_QUOTES,'UTF-8'); ?>"></span>
                            <?php echo htmlspecialchars($value['username'],ENT_QUOTES,'UTF-8'); ?>
                            <?php echo htmlspecialchars($value['age'],ENT_QUOTES,'UTF-8'); ?>
                            <?php echo htmlspecialchars($value['country'],ENT_QUOTES,'UTF-8'); ?>
                        </div>    
                            <?php if($value['url']!==""){ ?>
                                <div class="pic_posted"><?php echo createthumbnailtag($value['url']); ?></div>
                            <?php } ?>
                            <?php if($value['pic']!==""){ ?>
                                <div class="pic_posted"><img class="img-responsive pic_posted_img" src="./onlyme_pic/<?php echo htmlspecialchars($value['pic'],ENT_QUOTES,'UTF-8'); ?>"></div>
                            <?php } ?>    
                            <?php echo htmlspecialchars($value['title'],ENT_QUOTES,'UTF-8'); ?><br>
                        </a>
                        <!--me too,who clicked button-->
                        <div class="buttons">
                            <?php if(in_array($value['id'],$me_too_list,TRUE)===TRUE){ ?>
                                <button class="you_too" disabled>you too!<?php echo $value['count_me_too']; ?></button>
                            <?php }else{ ?>    
                                <button class="me_too" value="<?php echo $value['id'] ?>">me too!<?php echo $value['count_me_too']; ?></button>
                            <?php } ?>    
                            <a class="who_clicked" href="#" data-featherlight="#who_clicked<?php echo $key; ?>">☚<input type="submit" name="who_clicked"  value="who clicked?"></a>
                            <input type="hidden" name="hide" value="who_clicked">
                        </div>    
                    </td>     
                </div>
    
    <!--動画/画像部分クリックしたら出てくるfeather-light-->
            <div id="display<?php echo $key; ?>" class="feather-light-second">
                <?php echo createvideotag($value['url']); ?><br>
                <div>
                    <span class="who_clicked_profile_photo"><img src="../onlyme_pic/<?php echo htmlspecialchars($value['profile_photo'],ENT_QUOTES,'UTF-8') ?>"></span>
                    <?php echo htmlspecialchars($value['username'],ENT_QUOTES,'UTF-8'); ?>
                    <?php echo htmlspecialchars($value['age'],ENT_QUOTES,'UTF-8'); ?>
                    <?php echo htmlspecialchars($value['country'],ENT_QUOTES,'UTF-8'); ?>
                    <?php echo htmlspecialchars($value['date'],ENT_QUOTES,'UTF-8'); ?>
                </div>
                <div class="comment"><?php echo htmlspecialchars($value['comment'],ENT_QUOTES,'UTF-8'); ?></div><br>
        <!--metooボタンとwho clicked-->
                <div class="buttons">
                        <?php if(in_array($value['id'],$me_too_list,TRUE)===TRUE){ ?>
                            <button class="you_too" disabled>you too!<?php echo $value['count_me_too']; ?></button>
                        <?php }else{ ?>    
                            <button class="me_too" value="<?php echo $value['id'] ?>">me too!<?php echo $value['count_me_too']; ?></button>
                        <?php } ?>    
                        <a class="who_clicked" href="#" data-featherlight="#who_clicked<?php echo $key; ?>">☚<input type="submit" name="who_clicked"  value="who clicked?"></a>
                        <input type="hidden" name="hide" value="who_clicked">
                </div>    
        <!--commentをshareするとこ-->
                    <input type="text" class="comment_text" placeholder="Comment">
                    <button class="share_button" value="<?php echo $value['id'] ?>">submit</button>
        <!--コメントみんなが書き込みしあうぶぶんーーーー！！-->
                <?php   if(isset($who_comment_share[$value['id']])===TRUE) { ?>
                <ul>
                    <?php foreach(array_reverse($who_comment_share[$value['id']]) as $value_who_comment_share) { ?>
                        <li class="share_comment">
                            <span class="who_clicked_profile_photo"><img src="../onlyme_pic/<?php echo htmlspecialchars($value_who_comment_share['profile_photo'],ENT_QUOTES,'UTF-8') ?>"></span>
                            <?php if($value_who_comment_share['gender']==="male") { ?>
                                  <span class="male"><?php echo htmlspecialchars($value_who_comment_share['username'],ENT_QUOTES,'UTF-8'); ?></span>
                            <?php } ?>
                            <?php if($value_who_comment_share['gender']==="female") { ?>
                                  <span class="female"><?php echo $value_who_comment_share['username']; ?></span>
                            <?php } ?>
                            <?php echo htmlspecialchars($value_who_comment_share['age'],ENT_QUOTES,'UTF-8'); ?>
                            <?php echo htmlspecialchars($value_who_comment_share['country'],ENT_QUOTES,'UTF-8'); ?>:
                            <?php echo htmlspecialchars($value_who_comment_share['comment'],ENT_QUOTES,'UTF-8') ?>
                        </li>
                    <?php } ?>
                </ul>    
                <?php } ?>    
                <!--<div class="share_comment"></div> <!-- AJAXで表示させる用のdiv --!>
            </div>
        <!--#### WHO CLICKED?押したら出てくるfeatherlight!! ###-->
            <div id="who_clicked<?php echo $key; ?>" class="feather-light">
                <?php if(isset($who_clicked_select[$value['id']])===TRUE) { ?>
                    <?php foreach($who_clicked_select[$value['id']] as $value_clicked) { ?>
                        <p class="who_clicked_profile_photo">
                            <img src="../onlyme_pic/<?php echo htmlspecialchars($value_clicked['profile_photo'],ENT_QUOTES,'UTF-8') ?>">
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
                <?php } ?>    
            </div>    
        <?php } ?>
    </div>
</div>

<script src="//code.jquery.com/jquery-latest.js"></script>
<script src="//cdn.rawgit.com/noelboss/featherlight/1.5.0/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>

<!--sweetalertのcdn-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!--bootstrap cdn-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
// 俺のビデオにme_too押された時
$(function(){
        $('.me_too_my_video').click(function(){
        $container=$(this);
        $.ajax({
            type: "POST",
            url: "ajax_my_video.php",
            data: {
                user_id : <?php echo $user_id; ?>
            },
            success: function(json)
                {  
                    $container.removeClass('me_too').addClass('you_too').html('you too!'+json['count']).prop('disabled', true);
                },
            error: function(XMLHttpRequest,textStatus,errorThrown)
                {
                    alert('エラーです');
                }
        }); 
    return false;
    });
});
// me_tooボタン押したら起こる挙動(数字が増えて色が変わってyou too!っていう文字に変わる)
$(function(){
    $('.me_too').click(function(){ 
        $container=$(this);
        $.ajax({
            type: "POST",
            url: "ajax_me_too.php",
            data: {
                video_id : $(this).val(),
                user_id : <?php echo $user_id; ?>
            },  
            success: function(json)
                {  
                    $container.removeClass('me_too').addClass('you_too').html('you too!'+json['count']).prop('disabled', true); 
                },
            error: function(XMLHttpRequest,textStatus,errorThrown)
                {
                    alert('エラーです');
                }
        }); 
        return false;
    });
});    
$(function(){
    $('.share_button').click(function(){
        $container=$(this);
        $.ajax({
            type : "POST",
            url : "ajax_comment.php",
            data : {
                video_id : $(this).val(),
                comment : $(this).prev().val(), //お兄さん（コードの上）下の場合はnext
                user_id : <?php echo $user_id; ?>
            },
            success: function(json)
            {   
                $container.prev().val("");
                $container.next().html("");//ここで消さないと今までのコメントが二重になって表示されて俺がコメントしたのがでてきてしまう
                // ↑ここを$container.next().html("");にしても消えない汗
                for(var i=0; i<json.length; i++){
                    $container.next().append('<li class="share_comment"></li>')
                    $container.next().find('.share_comment:last-child').append('<span class="who_clicked_profile_photo"><img src="../onlyme_pic/'+json[i].profile_photo+'"></span>');
                    $container.next().find('.share_comment:last-child').append('<span class="'+json[i].gender+'">'+json[i].username+'</span> ');
                    $container.next().find('.share_comment:last-child').append(json[i].age+' '+json[i].country+':'+json[i].comment);
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
<!--//背景をランダムに出そうと挑戦したやつ、上にcdn有り-->
<!--        // $(&quot;.display&quot;).each(function(){  -->
<!--        //     $.data(this, &quot;hcolor&quot;, {  -->
<!--        //         r: Math.floor(Math.random()*50) + 190,  -->
<!--        //         g: Math.floor(Math.random()*50) + 190,  -->
<!--        //         b: Math.floor(Math.random()*50) + 190  -->
<!--        //     });  -->
<!--        // }).hover(  	function(){  -->
<!--        //     $(this).stop().animate({  -->
<!--        //         backgroundColor: &quot;rgb(&quot;  -->
<!--        //         + $.data(this,&quot;hcolor&quot;).r -->
<!--        //         + &quot;,&quot;  -->
<!--        //         + $.data(this,&quot;hcolor&quot;).g + &quot;,&quot;  -->
<!--        //         + $.data(this,&quot;hcolor&quot;).b + &quot;)&quot;  -->
<!--        //         },500);  -->
<!--        //     },  	function(){  -->
<!--        //         $(this).stop().animate({  -->
<!--        //             backgroundColor: &quot;rgb(255,255,255)&quot;  -->
<!--        //         },500);  -->
<!--        // }  	)-->