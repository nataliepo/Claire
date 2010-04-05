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
   var $blog_xid;
   
   function Comment($params) {


      // this is a POST request.
      if (array_key_exists('post_xid', $params) &&
          array_key_exists('content',  $params)) {

         if (array_key_exists('session', $params)) {
            $this->post_authenticated_comment($params);
            return;
         }
         else {
            // Anonymous comment posts require the 'name' and 'href' AND EMAIL AND BLOG_XID parameters.
            if ((array_key_exists('name', $params)) && 
                (array_key_exists('href', $params)) &&
                (array_key_exists('email', $params)) &&                
                (array_key_exists('blog_xid', $params))) {
               $this->post_anonymous_comment($params);
            }
            else {
               debug ("[Comment::Comment] Anonymous comments require 'name', 'href', 'email', and 'blog_xid parameters.");
               return;
            }

         }
            
         return;
      }

      // Allow creation of a Comment shell to allow other Commenting Services
      // to take on its form.
      if (!array_key_exists('xid', $params) &&
          !array_key_exists('json', $params)) {
         return;
      }

      if (!array_key_exists('json', $params)) {
         $params['json'] = pull_json(get_entry_api_url($params['xid']));
      }

      $this->author = new Author(array('xid' => $params['json']->author->urlId,        
                                       'json' => $params['json']->author));
      $this->content = $params['json']->content;
      $this->xid = $params['json']->urlId;

/*          $datetime =  new DateTime($comment_json->published);
          debug ("Timestamp would have been: " . $datetime->format('F d, Y g:ia'));
*/
      // FOR PHP 5.1.6 COMPATIBILITY.
      $this->timestamp = new TPDate($params['json']->published);
   }

       function get_content () {
          return $this->content;
       }  
       
       function time() {
          return $this->timestamp->print_readable_time();
       }


       function post_anonymous_comment ($params) {

          if (anon_comments_allowed($params['blog_xid'])) {
             debug ("[post_anonymous_comment] post_xid = " . $params['post_xid']);
        
             $json = '{"content":"' . $params['content'] . '",' . 
                       '"name":"'   . $params['name']    . '",' . 
                       '"href":"'   . $params['href']    . '",' .
                       '"email":"'  . $params['email']   . '"}';
             debug ("[post_anonymous_comment] json = $json");

             $typepad_url = get_comments_api_url ($params['post_xid']);
             $response = post_text($typepad_url, $json);
             debug ("[post_anonymous_comment] Response from TP Anon Comment Endpoint = $response");
             //function post_text ($url, $params, $decode=1) {
         }
         else {
            debug ("Anonymous comments aren't allowed.");
         }
          
       }
       
       function post_authenticated_comment ($params) {
          $typepad_url = get_comments_api_url ($params['post_xid'], 1);
          
          $session = $params['session'];
          $json = '{"content":"' . $params['content'] . '"}';
          $response = $session->make_authorized_request($typepad_url, $json);
          
       }
    }

class TPCommentListing {
   var $post_xid;
   var $comment_array;

      
   // contructor - expects the XID of the entry.
   function TPCommentListing($params) {
      if (!array_key_exists('xid', $params)) {
         debug ("[TPCommentListing::TPCommentListing] Expected parameter 'xid' in the constructor.");
         return;
      }
      
      $this->post_xid = $params['xid'];
      $this->comment_listing = array();
      
      $events = pull_json(get_comments_api_url($params['xid']));

      $i = 0;    
      foreach($events->{'entries'} as $comment) {
         $this->comment_array[$i] = new Comment(array('xid'  => $comment->urlId, 
                                                      'json' => $comment));
         $i++;
      }
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

   function build_comment_listing ($comments) {
/*      $facebook = new Facebook(FACEBOOK_API_KEY, FACEBOOK_API_SECRET);
      $comments = $facebook->api_client->comments_get($xid);
*/

      if (!$comments) {
         $this->size = 0;
         return;
      }

      foreach ($comments as $comment) {
         $fb_comment = new Comment();
/*         $user_record = $facebook->api_client->users_getInfo($comments[$i]['fromid'], 
                              'last_name, first_name, pic_with_logo, profile_url');
*/

         $fb_author = new Author();
         $fb_author->display_name = $comment->author->displayName;
         $fb_author->profile_url = $comment->author->profilePageUrl;
         $fb_author->avatar = $comment->author->avatar;

/*
         $fb_author->display_name = $user_record[0]['first_name'] . ' ' . $user_record[0]['last_name'];
         $fb_author->profile_url = $user_record[0]['profile_url'];
         $fb_author->avatar = $user_record[0]['pic_with_logo'];
*/
         
         // create the comment.
         $fb_comment->author = $fb_author;
         
//         $fb_comment->content = $comments[$i]['text'];
         $fb_comment->content = $comment->content;
 
//         $fb_comment->timestamp = print_timestamp_from_epoch($comments[$i]['time']);
         // FOR PHP 5.1.6 COMPATIBILITY.
//         $fb_comment->timestamp = new TPDate($comments[$i]['time']);
         $fb_comment->timestamp = new TPDate($comment->timestamp);

         $fb_comment->xid = 0;
         
         $this->comment_array[] = $fb_comment;
      }
   }
   
   // contructor
   function FBCommentListing($comment_obj='', $post_xid) {
      $this->post_xid = $post_xid;
      $this->comment_listing = array();
      if ($comment_obj) {
         $this->build_comment_listing($comment_obj);
      }
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


 