<html>
    <head>
        <?php 
            include_once('config.php');

            // provide a post_xid just in case.
            $post_xid = '6a00e5539faa3b88330120a7b004e2970b';
                
            if (array_key_exists('xid', $_GET)) {
               $post_xid = $_GET['xid'];
            }
            else if (array_key_exists('xid', $_POST)) {
               $post_xid = $_POST['xid'];
            }
            
            $user_session = new TPSession();
            
            
            // handle comment posts...
            if (array_key_exists('comment_text', $_POST)) {
                 if ($user_session->is_logged_in()) {
                   // This will post a comment to TypePad -- while Authenticated.
                   $comment = new Comment(array('post_xid' => $post_xid,
                                                'session'  => $user_session,
                                                'content'  => $_POST['comment_text']));
                 }
                 else {
                    // Post anonymously.
                    $comment = new Comment(array('post_xid' => $post_xid,
                                                 'content'  => $_POST['comment_text'], 
                                                 'name'     => $_POST['comment_name'],
                                                 'href'     => $_POST['comment_href']));
                 }
            }
     
            
            
            $entry = new Entry(array('xid' => $post_xid));
            $favorites = $entry->favorites();
            $comments = $entry->comments();
         ?>
         
         <title>Claire: <?php echo $entry->title; ?></title>
         

     <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

    </head>
        
    <body>
        <h2><a href="index.php">Claire Demo</a></h2>
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
           echo '<br /><a href="' . $entry->permalink . '" target="_blank">View Entry on TypePad</a>';
        ?>
        </div>        
        
        <?php
            echo '<h3><a href="' . $entry->author->profile_url . '"><img class="avatar" src="'. $entry->author->avatar . '" /></a>';
            echo  $entry->title . "</h3>";
            echo '<p>Posted at ' . $entry->time() . '</p>';
        ?>
              
      <div id="alpha">
         <div class="entry-body">
            <p><?php echo $entry->body; ?></p>
         </div>
         
         <div class="comments">
            <h5>Comments</h5>

               <?php

                  foreach ($comments as $comment) {
                     echo '
             <div class="comment-outer">
                <div class="comment-avatar">
                   <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
                </div>
                <div class="comment-contents">
                   <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a>
                   wrote <p>' . $comment->content . '</p> on ' . $comment->time() . '<br />
                </div>
             </div>
             <br />';                  
                  }  

               ?>
            </div>
            <br />
                        
      <?php
         if (!$user_session->is_logged_in()) {      
            echo "<h3><a href='login.php'>Sign In</a> or Comment Anonymously</h3>";
         }
         else {
            echo "<h3>Leave a Comment, <a href='" . $user_session->author->profile_url . "'>" . 
               $user_session->author->display_name . "</a>!</h3>";
      ?>
      <!-- comment form -->
      <form action="entry.php" method="post">
         <input type="hidden" name="xid" value="<?php echo $post_xid; ?>">
         <?php
            if (!$user_session->is_logged_in()) {
               echo '<label>Your Name: </label> <input type="text" name="comment_name" /><br />';
               echo '<label>Your Website: </label> <input type="text" name="comment_href" /><br />';
            }
         
         ?>

         <textarea name="comment_text" cols="40" rows="6"></textarea>
         <br />
         <input type="submit" value="send">
      </form>
   <?php
         }
   ?>      
      </div>
      
      <div id="beta"> 
        
        <div class="favorites">
           <h5>Favorites</h5>
          
       <?php 
            foreach ($favorites as $favorite) {
               echo '
            <div class="favorite-avatar">
               <a href="' . $favorite->author->profile_url . '">
                  <img class="avatar" src="' . $favorite->author->avatar . '" />
               </a>
               <a href="' . $favorite->author->profile_url . '">' . $favorite->author->display_name .
               '</a> favorited this entry on ' . $favorite->time() . '.
            </div>';
            }
        ?> 
           
        </div>
        
    </div> 
    </body>
</html>