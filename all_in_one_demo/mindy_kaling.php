<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');

   $url = 'http://nataliepo.typepad.com/nataliepo/2010/03/mindy-kaling-and-her-twitter-machine.html';
   // optional
   $content = ''; 
   $site_url = '';
   $timestamp = '';
   
   $comment_obj = post_text ('http://dev3.apperceptive.com/rousseau/comments.php',
                     "url=$url&content=$content&site_url=$site_url&timestamp=$timestamp");


   $comments = new FBCommentListing($comment_obj->comments, $comment_obj->xid);
?>

   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>

<body>
   
   <a href="index.php"><h2>TypePad Blog Post - FB Comments Only: "Mindy Kaling and her Twitter Machine"</h2></a>

   <a class="next" href="<?php echo $url; ?>" target="_blank">View Entry in TypePad</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_106566566031325&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php
      
      
      if (!$comments) {
         echo "<p>There aren't any comments yet.</p>";
      }
      else {
         foreach($comments->comments() as $comment) {
               echo '
         <div class="comment-outer">
            ' . 
            '<div class="comment-avatar">
               <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
            </div>
            <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote ' . 
            
               '<p>' . $comment->content . '</p>' . 
               ' on ' . 
                     $comment->time() . '<br />
            </div>
         </div>';                  
         }                
      }
      ?>
   </div>
   
</body>
</html>