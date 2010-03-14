<?php
    include_once('tp-utilities.php');
    

    class CommentListing {
        var $post_xid;
        var $comment_array;

         function build_comment_listing ($xid) {
             $events = pull_json(get_comments_api_url($xid));

             $i = 0;    
             foreach($events->{'entries'} as $comment) {
                 $this->comment_array[$i] = new Comment($comment);
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
        
        function comments() {
            return $this->comment_array;
        }
    }
    



class Comment {
    var $author_avatar;
    var $author_display_name;
    var $author_profile_page_url;
    var $content;
    
    // contructor
    function Comment($comment_json) {
        $this->author_avatar = get_resized_avatar($comment_json->author, 35);
        $this->author_display_name = $comment_json->author->displayName;
        $this->author_profile_page_url = $comment_json->author->profilePageUrl;
        $this->content = $comment_json->content;
    }
      
    function get_content () {
        return $this->content;
    }  
}
    
?>


 