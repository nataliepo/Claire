<?php

/****
 *  To get a listing of regular TypePad comments on this entry...
 *****/

$entry = new Entry($post_xid);
$comments = $entry->comments();   
foreach ($comments as $comment) {      
   echo 
'<div class="comment-outer">
   <div class="comment-avatar">
      <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
   </div>
   <div class="comment-contents">
      <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a>
      wrote <p>' . 
         $comment->content . 
      '</p> on ' . $comment->timestamp . '<br />
   </div>
</div>';
}

/****
 * To get a listing of just Facebook comments on this entry...
 *****/

$entry = new Entry($post_xid);
$fb_comments = $entry->fb_comments();
foreach ($fb_comments as $comment) {
   echo 
'<div class="comment-outer">
   <div class="comment-avatar">
      <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
    </div>
    <div class="comment-contents">
      <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a>
      wrote <p>' . 
        $comment->content . 
        '</p> on ' . $comment->timestamp . '<br />
   </div>
</div>';
}


?>