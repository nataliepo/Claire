<html>

<head>
   <title>Claire: Posting Comments Example</title>
   <?php 
      include_once('config.php'); 
            
      $post_xid = '6a00e5539faa3b88330120a7b004e2970b';
                
      $page_number = 1;
      if (array_key_exists('page', $_GET)) {
            $page_number = $_GET['page'];
      }
            
      $entry_listing = new EntryListing(array('xid' => BLOG_XID, 
                                              'page_number' => $page_number));
      
      $entries = $entry_listing->entries();
      
      $user_session = new TPSession();
      
   ?>
        
        
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

</head>
<body>
   <h2><a href="index.php">Claire Demo: Posting Comments</a></h2> 
   <div class="login_box">
<?php
   if (!$user_session->is_logged_in()) {      
      echo "<a href='login.php'>Log In</a>";
   }
   else {
      echo "Welcome, <a href='" . $user_session->author->profile_url . "'>" . 
            $user_session->author->display_name . "</a>!";
      echo '<br /><a href="index.php?logout=1">Log Out</a>';
   }       
?>
   </div>
   <h3>Here are the recent posts for the demo blog <a href="http://freebie.typepad.com/blog/">Freebie</a>.</h3> 

   <div class="entries">  
   <?php
      foreach ($entries as $entry) {
         echo "<h4>";
                
         echo '<a href="entry.php?xid=' . $entry->xid . '">';
         echo $entry->title . "</a></h4>";
                
         if ($entry->thumbnail) {
            echo '<img src="' . $entry->thumbnail . '" />';
         }
         echo $entry->excerpt();
         echo  "<br /><br /><br />";
      }
   ?>
      <br />
   </div>
   <hr />
        
   <?php  
      if($page_number == 1) {
         echo "<a class='next' href='index.php?page=" . ($page_number + 1) . "'>Next Page &gt;&gt;</a>";
      }
      else {
         echo "<a class='prev' href='index.php'>&lt;&lt; Previous Page</a>";
      }
   ?> 
</body>
</html>