<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
<?php
   include_once('config.php');
   $user_session = new TPSession();

?>
   
</head>

<body>
   
   <h2>Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</h2>

   <div class="login_box">
<?php
      if (!$user_session->is_logged_in()) {      
         echo "<a href='login.php'>Log In</a><br />";
      }
      else {
         echo "Welcome, <a href='" . $user_session->author->profile_url . "'>" . 
               $user_session->author->display_name . "</a>!<br />";
         echo '<a href="index.php?logout=1">Log Out</a><br />';
      }
?>  
      <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>
</div>

   <ol>
      <li><a href="sea_otter.php">Sea Otter</a></li>
      <li><a href="sloth.php">Sloth</a></li>
      <li><a href="rabbit.php">Rabbit</a></li>
      <li><a href="treasury_dept.php">Treasury Department</a></li>
      <li><a href="performance_artist.php">Performance Artist at the MoMa</a></li>


   </ol>
   
</body>
</html>