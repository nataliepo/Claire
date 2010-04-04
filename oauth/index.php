<?php
 
   include_once ("oauth-php-98/library/OAuthStore.php");
   include_once ("oauth-php-98/library/OAuthRequester.php");

   include_once('config.php'); 

   //----------------------
   // The TPSession constructor is handling a lot of session-housekeeping:
   // 1. If this is the final step of the OAuth dance, it will fetch the final 
   //    access token and store it locally
   // 2. If you're already logged in, and you've already completed the OAuth dance,
   //    it will keep your access token and verifier locally, along with your user_id
   // 3. If you're logging out, it will remove your local session (but won't affect your TP one)
   //----------------------
   $user_session = new TPSession();
?>


<html>
<head>
   <title>TP Auth Example</title>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

</head>

<body>
   <h2><a href="index.php">TypePad Loves You!</a></h2>
<?php
   
   if (!$user_session->is_logged_in()) {      
      echo "<h3 align='center'><a href='login.php'>Log In</a>!</h3>";
   }
   else {
      echo '<a class="next" href="index.php?logout=1">Log Out</a>';
      
      echo "<h3 align='center'>Welcome, <a href='" . $user_session->author->profile_url . "'>" . 
            $user_session->author->display_name . "</a>!</h3>";
   }
?>
<h5>Known Users</h5>
<?php
   $users = get_local_users_list();
   
   if (sizeof($users)) {
      echo "<ul>";
      foreach ($users as $user) {
         // Checking if the xid is valid before I make an author record...
         $pattern = '/^(6p.+)$/';
         preg_match($pattern, $user['user_tp_xid'], $matches);

         if (sizeof($matches) > 0) {
            $author = new Author(array('xid' => $user['user_tp_xid']));
            echo "<li><img src='" . $author->avatar . "' /> " . 
                     "<a href='" . $author->profile_url . "'>". $author->display_name . "</a></li>";
         }
      }
      echo "</ul>";
   }
   else {
      echo "<p>There aren't any users in this system.</p>";
   }

?>


</body>
</html>
