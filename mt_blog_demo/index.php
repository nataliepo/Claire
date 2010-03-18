<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html',
                                  '61');

   $comments = $tp_entry->braided_comments();

?>

   
</head>

<body>
   
   <h2>Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</h2>

   <a class="next" href="http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html" target="_blank">View Entry on Movable Type</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php

         foreach ($comments as $comment) {
            echo '
      <div class="comment-outer">
         <div class="comment-avatar">
            <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
         </div>
         <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote <p>' . $comment->content . '</p> on ' . $comment->timestamp . '<br />
         </div>
      </div>';                  
         }
      ?>
   </div>
   
</body>
</html>