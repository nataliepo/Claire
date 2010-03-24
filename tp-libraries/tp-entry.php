<?php
    include_once('tp-utilities.php');
    

class EntryListing {
     var $entry_array; 
       
       function build_entries_listing ($page_number) {
          $events = pull_json(get_entries_api_url($page_number));

          $i = 0;    
          foreach($events->{'entries'} as $entry) {
              $this->entry_array[$i] = new Entry($entry->urlId, $entry);
              $i++;
          }
      }

     // contructor
     function EntryListing($page_number = 1) {
         $this->entry_listing = array();
         $this->build_entries_listing($page_number);
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
     
     function entries() {
         return $this->entry_array;
     }
}
 

class TPConnectEntry {
   var $xid;
   var $entry_id;
   var $tp_comment_listing;
   var $fb_comment_listing;
   var $braided_listing;
   var $blog_xid;
   var $permalink;
   var $timestamp;
   
   var $content;
   
   function TPConnectEntry ($blog_xid, $permalink, $entry_id, $timestamp, $content) {
      $this->entry_id = $entry_id;

      $json = '{"permalinkUrl":"' . $permalink . '"}';
      $post_url = get_tpconnect_external_assets_api_url($blog_xid);
      # First, get the 
      $events = post_json($post_url, $json);
//      var_dump($events);
      $this->xid = $events->asset->urlId;
      
      $this->tp_comment_listing = array();
      $this->blog_xid = $blog_xid;
      $this->permalink = $permalink;
      $this->content = $content;
      $this->timestamp = $timestamp;
   }
   
   function comments() {
      if (!$this->tp_comment_listing) {
         $this->build_tp_comment_listing();
      }
       
       return $this->tp_comment_listing->comments();
    }
    
    function build_tp_comment_listing () {
        $this->tp_comment_listing = new TPCommentListing($this->xid);
    }

    function build_fb_comment_listing() {
//       $comments = new FBCommentListing($comment_obj->comments, $comment_obj->xid);
       
        $this->fb_comment_listing = new FBCommentListing('', FACEBOOK_POST_ID_PREFIX . $this->entry_id);
    }

    function build_braided_listing() {
       $this->braided_listing = new BraidedCommentListing($this->fb_comment_listing->comments(), 
                                                          $this->tp_comment_listing->comments());
   }
   
   function rousseaus_listing() {                                                       
                                                          
         // Use the Rousseau server.
        /*
         * ?
         * blog_xid=6a00e5539faa3b88330120a94362b9970b
         * permalink=http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sea-otter.php
         * fb_id=fb-animals-60
      */
      $escaped_content = urlencode($this->content);
        $params = "blog_xid=" . $this->blog_xid . "&" . 
                  "permalink=" . $this->permalink . "&" . 
                  "fb_id=" . FACEBOOK_POST_ID_PREFIX . $this->entry_id . "&" . 
                  "content=" . $escaped_content . "&" . 
                  "timestamp=" . $this->timestamp . "&" .
                  "HTML=1";
         
        return rousseaus_comments($params);
    }    
    
   
   function braided_comments() {
       if (!$this->braided_listing) {          
          $this->build_braided_listing();
       }
       return $this->braided_listing->comments();
    }

}

class Entry {
    var $title;
    var $body;
    var $permalink;
    var $thumbnail;
    var $xid;
    var $timestamp;
    
    var $author;
    
    var $comment_listing;
    var $favorite_listing;
    var $fb_comment_listing;
    var $braided_listing;
    
    // contructor
    //  TWO INPUT TYPES: a JSON entry object, or an XID.
    // passing a JSON object reduces the number of requests.
    // passing an XID makes another request to get lots of information.
    function Entry($xid, $entry_json = '') {
       if ($entry_json == '') {
          $entry_json = pull_json(get_entry_api_url($xid));
       }
       
       // otherwise, ($type == 'json'), format is ready to parse. 
       $this->title = get_entry_title($entry_json);
       $this->body = $entry_json->content;
       $this->permalink = $entry_json->permalinkUrl;
       $this->thumbnail = get_first_thumbnail($entry_json->embeddedImageLinks);
       $this->xid = $entry_json->urlId;
       $this->author = new Author($entry_json->author->urlId, $entry_json->author);

       // GETTING RID OF DateTime for PHP 5.1.6 compatibility
//       $date =  new DateTime($entry_json->published);
//       $this->timestamp = print_timestamp($date);
      $this->timestamp = new TPDate($entry_json->published);

    }
    
      
    function title () {
        return $this->title;
    }  
    
    function body () {
        return $this->body;
    }
    
    function excerpt($size = 150) {
        return chop_str($this->body, $size);
    }
    
    function build_comment_listing () {
       $this->comment_listing = new TPCommentListing($this->xid);
    }
    
    function build_favorite_listing() {
       $this->favorite_listing = new FavoriteListing($this->xid);
    }
    
    function build_fb_comment_listing() {
       $this->fb_comment_listing = new FBCommentListing(FACEBOOK_POST_ID_PREFIX . $this->xid);
    }
    
    function build_braided_listing() {
       $this->braided_listing = new BraidedCommentListing($this->fb_comment_listing->comments(), 
                                                          $this->comment_listing->comments());
    }
    
    
    function braided_comments() {
       
       if (!$this->braided_listing) {
          
          if (!$this->fb_comment_listing) {
             $this->build_fb_comment_listing();
          }
          
          if (!$this->comment_listing) {
             $this->build_comment_listing();
          }
          
          $this->build_braided_listing();
       }
       return $this->braided_listing->comments();
       
     
       
    }
    
    function comments() {
      if (!$this->comment_listing) {
         $this->build_comment_listing();
      }
       
       return $this->comment_listing->comments();
    }
    
    function favorites() {
       if (!$this->favorite_listing) {
          $this->build_favorite_listing();
       }
       
       return $this->favorite_listing->favorites();
    }
    
    function fb_comments() {
       if (!$this->fb_comment_listing) {
          $this->build_fb_comment_listing();
       }
       return $this->fb_comment_listing->comments();
    }

    function time() {
        return $this->timestamp->print_readable_time();
    }

}
 
 
 
/*** Makes the Rousseau request. ***/
function rousseaus_comments ($params) {
//   return pull_json(ROUSSEAU_COMMENTS_URL . '?' . $params, 0);
   return post_text(ROUSSEAU_COMMENTS_URL, $params, 0);
} 
 
    
?>


 