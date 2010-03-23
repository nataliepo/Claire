<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   $comments = post_text ('http://dev3.apperceptive.com/rousseau/comments.php','url=http://nataliepo.typepad.com/nataliepo/2010/03/mindy-kaling-and-her-twitter-machine.html');
?>

   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>

<body>
   
   <a href="index.php"><h2>TypePad Blog Post - FB Comments Only.</h2></a>

   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_106566566031325&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="entry">
      <h3>Posts</h3>
      <ul>
         <li><a href="justin_bieber.php">Justin Bieber</a></li>
         <li><a href="mindy_kaling.php">Mindy Kaling</a></li>
         <li><a href="nmcm_gaga_dogs.php">Not Modcult Material: Dogs Dressed as Lady Gaga</a></li>
      </ul>
   </div>
   
</body>
</html>