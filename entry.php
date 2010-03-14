<html>
    <head>
        <?php 
            include_once('tp-libraries/tp-utilities.php'); 
     
            // provide a post_xid just in case.
            $post_xid = '6a00e5539faa3b88330120a7b004e2970b';
                
            if ( ($_SERVER['REQUEST_METHOD'] == 'GET') &&
                ( $_GET['xid'])) {
                    $post_xid = $_GET['xid'];
            }
            
            $entry = new Entry($post_xid);
            $comments = $entry->comments();   
            $favorites = $entry->favorites();
         ?>
         <title>Rousseau: <?php echo $entry->title; ?></title>
         

     <link rel="stylesheet" href="styles.css" type="text/css" />

    </head>
        
    <body>
        <h2><a href="index.php">Rousseau Demo</a></h2>
        
        <h3><?php echo $entry->title; ?></h3>
        
      <div id="alpha">
         <div class="entry-body">
            <p><?php echo $entry->body; ?></p>
         </div>
      </div>
      
      <div id="beta"> 
        
        <div class="favorites">
           <h5>Favorites</h5>
          
       <?php 
            foreach ($favorites as $favorite) {
               echo
'<div class="favorite-avatar">
   <a href="' . $favorite->author_profile_page_url . '"><img src="' . $favorite->author_avatar . '" /></a>
</div>';
            }
        ?> 
           
        </div>
        
        <div class="comments">
           <h5>Comments</h5>
        <?php
            foreach ($entry->comments() as $comment) {
            
                echo 
'<div class="comment-outer">
    <div class="comment-avatar">
        <a href="' . $comment->author_profile_page_url . '"<img src="' . $comment->author_avatar. '" /></a>
    </div>
    <div class="comment-contents">
        <p>' . 
            $comment->content . 
        '</p>
    </div>
</div>';
            }
        ?>
        </div>
        
        
      </div> 
    </body>
</html>