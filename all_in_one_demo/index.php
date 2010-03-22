<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   $fb_comments = post_text ('http://dev3.apperceptive.com/rousseau/comments.php','url=http://nataliepo.typepad.com/nataliepo/2010/03/mindy-kaling-and-her-twitter-machine.html');
   
   var_dump($fb_comments);
   debug ("RESULTS from post_text() ^^");
   
   

?>

   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>

<body>
   
   <h2>TypePad Blog Post - FB Comments Only.</h2>

   <a class="next" href="http://nataliepo.typepad.com/nataliepo/2010/03/mindy-kaling-and-her-twitter-machine.html" target="_blank">View Entry in TypePad</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_106566566031325&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php

      /*   foreach ($comments as $comment) {
            echo '
      <div class="comment-outer">
         <div class="comment-avatar">
            <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
         </div>
         <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote <p>' . 
               $comment->content . '</p> on ' . $comment->time() . '<br />
         </div>
      </div>';                  
         } 
    */
      ?>
      <p>Empty for now.</p>
   </div>
   
</body>
</html>