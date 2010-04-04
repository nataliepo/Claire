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
   
   <h5>Here are the comments of a post from <a href="http://nataliepo.typepad.com/hobbitted/2010/01/wtf-gandalf.html">WTF, Gandalf?</a>, a post from the <a href="http://nataliepo.typepad.com/hobbitted">Hobbitted</a> blog:</h5>
   <?php
   
      $entry_xid = "6a00e5539faa3b88330120a7b01470970b";
      
      /*
       * This results in 2 API calls -- one for the Entry, one for the Comments 
       *
      $entry = new Entry(array(xid => $entry_xid));                                             
      $comments = $entry->comments();
      */
      $comment_listing = new TPCommentListing(array('xid' => $entry_xid));
      $comments = $comment_listing->comments();
      
      echo "<ul>";
      
      foreach ($comments as $comment) {
                 echo '
         <div class="comment-outer">
            <div class="comment-avatar">
               <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
            </div>
            <div class="comment-contents">
               <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a>
               wrote <p>' . $comment->content . '</p> on ' . $comment->time() . '<br /><br />
            </div>
         </div>';                  
      } 
      
      echo "</ul>";  
   ?>
<hr />
  
</body>
</html>
