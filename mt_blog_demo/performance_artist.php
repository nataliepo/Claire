<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   
   
   $user_session = new TPSession();
   
   $content = '<b>Rabbits</b> are small <a href="http://en.wikipedia.org/wiki/Mammal" title="Mammal">mammals</a> in the <a href="http://en.wikipedia.org/wiki/Family_%28biology%29" title="Family (biology)">family</a> <a href="http://en.wikipedia.org/wiki/Leporidae" title="Leporidae">Leporidae</a> of the order <a href="http://en.wikipedia.org/wiki/Lagomorpha" title="Lagomorpha">Lagomorpha</a>, found in several parts of the world. There are seven different <a href="http://en.wikipedia.org/wiki/Genus" title="Genus">genera</a> in the family <a href="http://en.wikipedia.org/wiki/Taxonomy" title="Taxonomy">classified</a> as rabbits, including the <a href="http://en.wikipedia.org/wiki/European_rabbit" title="European rabbit" class="mw-redirect">European rabbit</a> (<i>Oryctolagus cuniculus</i>), <a href="http://en.wikipedia.org/wiki/Cottontail_rabbit" title="Cottontail rabbit">Cottontail rabbit</a> (genus <i>Sylvilagus</i>; 13 <a href="http://en.wikipedia.org/wiki/Species" title="Species">species</a>), and the <a href="http://en.wikipedia.org/wiki/Amami_rabbit" title="Amami rabbit" class="mw-redirect">Amami rabbit</a> (<i>Pentalagus furnessi</i>, <a href="http://en.wikipedia.org/wiki/Endangered_species" title="Endangered species">endangered species</a> on <a href="http://en.wikipedia.org/wiki/Amami_%C5%8Cshima" title="Amami Ōshima">Amami Ōshima</a>, <a href="http://en.wikipedia.org/wiki/Japan" title="Japan">Japan</a>). There are many other species of rabbit, and these, along with <a href="http://en.wikipedia.org/wiki/Pika" title="Pika">pikas</a> and <a href="http://en.wikipedia.org/wiki/Hare" title="Hare">hares</a>, make up the <a href="http://en.wikipedia.org/wiki/Order_%28biology%29" title="Order (biology)">order</a> <a href="http://en.wikipedia.org/wiki/Lagomorpha" title="Lagomorpha">Lagomorpha</a>. ';
   $post_url = 'http://mtcs-demo.apperceptive.com/testmt/recent_news/2010/03/performance-artist-mimics-performance-artist-at-moma.php';
   
   $tp_entry = new TPConnectEntry(array('blog_xid'  => '6a00e5539faa3b88330120a94362b9970b', 
                                        'permalink' => $post_url,
                                        'entry_id'  => '67',
                                        'content'   => $content));

   // handle comment posts...
   if ($user_session->is_logged_in() and
       array_key_exists('comment_text', $_POST)) {
      // This will post a comment to TypePad.
      $comment = new Comment(array('post_xid' => $tp_entry->xid,
                                   'session'   => $user_session,
                                   'content'  => $_POST['comment_text']));
   }

   $comments = $tp_entry->rousseaus_listing();

?>

   
</head>

<body>
   
   <h2><a href="index.php">Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</a></h2>

   <a class="next" href="<?php echo $post_url; ?>" target="_blank">View Entry on Movable Type</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

<?php
   echo $comments;
   
   if (!$user_session->is_logged_in()) {      
      echo "<h3><a href='login.php'>Sign In</a> to Comment</h3>";
   }
   else {
      echo "<h3>Leave a Comment, <a href='" . $user_session->author->profile_url . "'>" . 
            $user_session->author->display_name . "</a>!  Or, you can <a href='index.php?logout=1'>Log Out</a>.</h3>";
?>
      
      <!-- comment form -->
      <form action="#" method="post">
         <!--<input type="hidden" name="xid" value="<?php echo $post_xid; ?>">-->
         <textarea name="comment_text" cols="40" rows="6"></textarea>
         <br />
         <input type="submit" value="send">
      </form>
   <?php
         }
/*
         foreach ($comments as $comment) {
            echo '
      <div class="comment-outer">
         <div class="comment-avatar">
            <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
         </div>
         <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote <p>' . 
               $comment->content . '</p> on ' . $comment->time() . '<br />
         </div>
      </div>';                  
         }
*/
      ?>
   </div>
   
</body>
</html>