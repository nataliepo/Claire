<?php
    include_once('tp-utilities.php');
    

    class CommentListing {
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
        function CommentListing($post_xid = "") {
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
       
       if ($comment_json == '') {
          $comment_json = pull_json(get_entry_api_url($xid));
       }
       
        $this->author = new Author($comment_json->author->urlId, $comment_json->author);
        $this->content = $comment_json->content;
        $this->xid = $comment_json->urlId;
        
        $date =  new DateTime($comment_json->published);
        $this->timestamp = print_timestamp($date);
    }
      
    function get_content () {
        return $this->content;
    }  
}
    
    
class FBCommentListing {
   var $post_xid;
   var $comment_array; 

   function build_comment_listing ($xid) {
      $facebook = new Facebook(FACEBOOK_API_KEY, FACEBOOK_API_SECRET);

      $comments = $facebook->api_client->comments_get(FACEBOOK_POST_ID_PREFIX . $xid);
      
      //debug("[FBCommentListing::build_comment_listing] FB comments_get() response = " . var_dump($comments));
      
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
//         $date = new DateTime->from_epoch($comments[$i]['time']); 
 //        debug ("[FBCommenter]: " . print_timestamp($date));
         $fb_comment->timestamp = $comments[$i]['time'];
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
?>


 