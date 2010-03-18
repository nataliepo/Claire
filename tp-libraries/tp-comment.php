<?php
    include_once('tp-utilities.php');
    
    
    /**********
     * Comment objects are agnostic of platform.  They can be
     * TypePad, FB, or another type of comment, but they always have:
     *    $author, which is an Author object (defined in tp-author.php)
     *    $content, which is just a string that represents the comment.
     *    $xid is the TP Entry's XID if this is a TP Comment.
     *    $timestamp is the date/time of the comment's posting
     ***************************/
    class Comment {
       var $author;
       var $content;
       var $xid;
       var $timestamp;

       // contructor
       //  TWO INPUT TYPES: a JSON entry object, or an XID.
       // passing a JSON object reduces the number of requests.
       // passing an XID makes another request to get lots of information.
       function Comment($xid = 0, $comment_json = '') {

          // Allow creation of a Comment shell to allow other Commenting Services
          // to take on its form.
          if (!$xid) {
             return;
          }

          if (!$comment_json) {
             $comment_json = pull_json(get_entry_api_url($xid));
          }

          $this->author = new Author($comment_json->author->urlId, $comment_json->author);
          $this->content = $comment_json->content;
          $this->xid = $comment_json->urlId;

/*          $datetime =  new DateTime($comment_json->published);
          debug ("Timestamp would have been: " . $datetime->format('F d, Y g:ia'));
*/
         // FOR PHP 5.1.6 COMPATIBILITY.
          $this->timestamp = new TPDate($comment_json->published);
       }

       function get_content () {
          return $this->content;
       }  
       
       function time() {
          return $this->timestamp->print_readable_time();
       }
    }

class TPCommentListing {
   var $post_xid;
   var $comment_array;

   function build_comment_listing ($xid) {
      $events = pull_json(get_comments_api_url($xid));

      $i = 0;    
      foreach($events->{'entries'} as $comment) {
         $this->comment_array[$i] = new Comment($comment->urlId, $comment);
         $i++;
      }
   }
      
   // contructor
   function TPCommentListing($post_xid = "") {
      $this->post_xid = $post_xid;
      $this->comment_listing = array();
      $this->build_comment_listing($post_xid);
   }
     
   function get_post_xid() {
      return $this->post_xid;
   }
     
   function comment($index) {
      if ($this->comment_array[$index]) {
         return $this->comment_array[$index];
      }
      return "";
   }
     
   function size() {
      if (!$this->comment_array) {
         return 0;
      }

      return sizeof($this->comment_array);
   }
     
   function comments() {
      if ($this->size() == 0) {
         return array();
      }

      return $this->comment_array;
   }
}


class FBCommentListing {
   var $post_xid;
   var $comment_array; 

   function build_comment_listing ($xid) {
      $facebook = new Facebook(FACEBOOK_API_KEY, FACEBOOK_API_SECRET);
      $comments = $facebook->api_client->comments_get(FACEBOOK_POST_ID_PREFIX . $xid);

      if (!$comments) {
         $this->size = 0;
         return;
      }
      
      for ($i = 0; $i < sizeof($comments); $i++) {
         $fb_comment = new Comment();
         $user_record = $facebook->api_client->users_getInfo($comments[$i]['fromid'], 
                              'last_name, first_name, pic_with_logo, profile_url');
         $fb_author = new Author();

         $fb_author->display_name = $user_record[0]['first_name'] . ' ' . $user_record[0]['last_name'];
         $fb_author->profile_url = $user_record[0]['profile_url'];
         $fb_author->avatar = $user_record[0]['pic_with_logo'];
         
         // create the comment.
         $fb_comment->author = $fb_author;
         $fb_comment->content = $comments[$i]['text'];
 
//         $fb_comment->timestamp = print_timestamp_from_epoch($comments[$i]['time']);
         // FOR PHP 5.1.6 COMPATIBILITY.
         $fb_comment->timestamp = new TPDate($comments[$i]['time']);

         $fb_comment->xid = 0;
         
         $this->comment_array[] = $fb_comment;
      }
   }
   
   // contructor
   function FBCommentListing($post_xid = "") {
      $this->post_xid = $post_xid;
      $this->comment_listing = array();
      $this->build_comment_listing($post_xid);
   }  
   
   function size () {
      if (!$this->comment_array) {
         return 0;
      }
      
      return sizeof($this->comment_array);
   }
   
   function comments() {
      if ($this->size() == 0) {
         return array();
      }
      
      return $this->comment_array;
   }
}

class BraidedCommentListing {
   var $fb_comments;
   var $tp_comments;
   var $braided_comments;
 
   function BraidedCommentListing($tp_comments, $fb_comments) {
      $this->fb_comments = $fb_comments;
      $this->tp_comments = $tp_comments;
      
		// Now, construct a hash based on their timestamps.
		$this->braided_comments = braid_comments($tp_comments, $fb_comments);
   }
   
   function size () {
      if (!$this->braided_comments) {
         return 0;
      }
      
      return sizeof($this->braided_comments);
   }
   
   function comments() {
      if ($this->size() == 0) {
         return array();
      }

      return $this->braided_comments;
   }
}

/* 
 * Utility method to sort up to 3 arrays based on Comment->timestamp.
 */
function braid_comments ($list1, $list2, $list3='') {
   if ($list3 == '') {
      $list3 = array();
   }
   
   $merged_array = array_merge($list1, $list2, $list3);
   $final_array = array();   
   
   foreach ($merged_array as $comment) {
      $final_array[$comment->timestamp->print_sortable_time()] = $comment;
   }

   // Sort by the timestamps.
   // This is sorted REVERSE chronologically -- newest comments first.
   krsort($final_array);
      
   // This is sorted chronologically -- oldest comments first.
   // ksort($this->comment_array);
   
   return $final_array;   
}   

?>


 