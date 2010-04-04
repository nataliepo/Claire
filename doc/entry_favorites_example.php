<html>

<head>
   <title>Claire</title>
   <?php 
      include_once('../tp-libraries/tp-utilities.php'); 
      define ("DEFAULT_DEBUG_MODE", 1); 
   ?>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>
<body>
   
   <h2>Blog Entries Demo</h2>
   
   <h5>Here are the favorites of a post from <a href="http://nataliepo.typepad.com/hobbitted/2010/01/plotzed.html">plotzed</a>, a post from the <a href="http://nataliepo.typepad.com/hobbitted">Hobbitted</a> blog:</h5>
   <?php
   
      $entry_xid = "6a00e5539faa3b88330120a7b004e2970b";
      
      /*
       * This results in 2 API calls -- one for the Entry, one for the Favorites 
       *
      $entry = new Entry(array(xid => $entry_xid));                                             
      $favorites = $entry->favorites();
      */
      $favorites_listing = new FavoriteListing(array(xid => $entry_xid));
      $favorites = $favorites_listing->favorites();
      
      echo "<ul>";
      
      foreach ($favorites as $favorite) {
          echo '
       <div class="favorite-avatar">
          <img class="avatar" src="' . $favorite->author->avatar . '" />
          <a href="' . $favorite->author->profile_url . '">' . $favorite->author->display_name .
          '</a> favorited this entry on ' . $favorite->time() . '.
       </div>';
      }
      
      echo "</ul>";  
   ?>
<hr />
  
</body>
</html>
