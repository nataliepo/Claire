<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   $comments = post_text ('http://dev3.apperceptive.com/rousseau/comments.php',
         'url=http://nataliepo.typepad.com/nataliepo/2010/03/not-modcult-material-dogs-dressed-as-lady-gaga.html');
?>

   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>

<body>
   
   <a href="index.php"><h2>TypePad Blog Post - FB Comments Only: "Not Modcult Material: Dogs Dressed as Lady Gaga"</h2></a>

   <a class="next" href="http://nataliepo.typepad.com/nataliepo/2010/03/not-modcult-material-dogs-dressed-as-lady-gaga.html" target="_blank">View Entry in TypePad</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_106566566031325&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php
      var_dump($comments);
      
      if (!$comments) {
         echo "<p>There aren't any comments yet.</p>";
      }
      else {
         
         foreach($comments->{'entries'} as $comment) {
               echo '
         <div class="comment-outer">
            <div class="comment-contents">' .
                  $comment->content . ' at ' . $comment->timestamp . '<br />
            </div>
         </div>';                  
            } 
      }
      ?>
      <p>Empty for now.</p>
   </div>
   
</body>
</html>