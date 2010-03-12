<html>
    <head>
        <title>braided</title>
        <?php include_once('tp-libraries/tp-comment.php'); ?>
    </head>
        
    <body>
        <h2>Rousseau Demo</h2>
        
        <h4>Here are the comments for the blog post <a href="http://nataliepo.typepad.com/hobbitted/2010/01/plotzed.html">Plotzed</a>
        from my <a href="http://nataliepo.typepad.com/hobbitted">Hobbited</a> blog.</h4>
        
        <?php
            $post_xid = '6a00e5539faa3b88330120a7b004e2970b';
                
          /*  if ( ($_SERVER['REQUEST_METHOD'] == 'GET') &&
                ( $_GET['xid'] != '')) {
                    $post_xid = $_GET['xid'];
            }
        */
            
            
            $comment_listing = new CommentListing($post_xid);
            $comments = $comment_listing->comments();

            
//            for ($i = 0; $i < sizeof($comments); $i++) {
            foreach ($comments as $comment) {
            
                echo 
'<div class="comment-outer">
    <div class="comment-avatar">
        <img src="' . $comment->author_avatar. '" />
    </div>
    <div class="comment-contents">
        <p>
            <a href="' . $comment->author_profile_page_url . '">' . $comment->author_display_name . '</a>
            said ' . $comment->content . '
        </p>
    </div>
</div>';
            }
        ?>
    
    </body>
</html>