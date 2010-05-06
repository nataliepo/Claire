<html>

<head>
   <title>Claire</title>
   <?php 
      define ("DEFAULT_DEBUG_MODE", 1);
   
      include_once('../tp-libraries/tp-utilities.php'); 

      $page_number = 1;
      if (array_key_exists('page', $_GET)) {
            $page_number = $_GET['page'];
      }
      
   ?>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>
<body>
   
   <h2>Blog Entries Demo</h2>
   
   <h5>Here are the most recent entries of the <a href="http://nataliepo.typepad.com/hobbitted">Hobbitted</a> blog:</h5>
   <ol>
   <?php
   
      // This is the Hobbitted (http://nataliepo.typepad.com/hobbitted) blog XID.
      $blog_xid = "6a00e5539faa3b88330120a7aa0fdb970b";
      $posts_per_page = 5;
      
      $blog_entry_listing = new EntryListing(array('xid' => $blog_xid,
                                                   'page_number' => $page_number,
                                                   'posts_per_page' => $posts_per_page));
                                                   
      echo "<ul>";
      $entries_array = $blog_entry_listing->entries();
      foreach ($entries_array as $entry) {
         echo "<li><a href='" . $entry->permalink . "'>" . $entry->title . 
            "</a>, XID = " . $entry->xid . "<br /><br /></li>";
      } 
      
      echo "</ul>";  
   ?>
<hr />
<?php  
     if($blog_entry_listing->has_next_page()) {
        echo "<a class='next' href='?page=" . ($page_number + 1) . "'>Next Page &gt;&gt;</a>";
     }
     
     if ($blog_entry_listing->has_prev_page()) {
        echo "<a class='prev' href='?page=" . ($page_number - 1) . "'>&lt;&lt; Previous Page</a>";
     }
  ?>   
</body>
</html>
