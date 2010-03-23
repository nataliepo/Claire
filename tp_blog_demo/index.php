<html>

<head>
   <title>Claire</title>
   <?php 
      include_once('config.php'); 
            
      $post_xid = '6a00e5539faa3b88330120a7b004e2970b';
                
      $page_number = 1;
      if ( ($_SERVER['REQUEST_METHOD'] == 'GET') &&
         ( $_GET['page'] != '')) {
            $page_number = $_GET['page'];
      }
            
      $entry_listing = new EntryListing($page_number);
      $entries = $entry_listing->entries();
   ?>
        
        
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

</head>
<body>
   <h2><a href="index.php">Claire Demo</a></h2>     
   <h4>Here are the recent posts for the blog <a href="http://nataliepo.typepad.com/hobbitted/">Hobbitted</a>.</h4> 
   <div class="entries">  
   <?php
      foreach ($entries as $entry) {
         echo "<h3>";
                
         echo '<a href="entry.php?xid=' . $entry->xid . '">';
         echo $entry->title . "</a></h3>";
                
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
         echo "<a class='next' href='index.php?page=2'>Next Page &gt;&gt;</a>";
      }
      else {
         echo "<a class='prev' href='index.php'>&lt;&lt; Previous Page</a>";
      }
   ?> 
</body>
</html>