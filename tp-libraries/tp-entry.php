<?php
    include_once('tp-utilities.php');
    



class TPConnectEntry {
   var $xid;
   var $entry_id;
   var $tp_comment_listing;
   var $fb_comment_listing;
   var $braided_listing;
   var $blog_xid;
   var $permalink;
   
   var $content;
   
   function TPConnectEntry($params) {
      if (!array_key_exists('post_xid', $params)) {
         if ((!array_key_exists('blog_xid',  $params)) && 
             (!array_key_exists('permalink', $params))) {
            debug ("[TPConnectEntry::TPConnectEntry] Either a post_xid OR blog_xid and permalink are required.");
         }
      }
      
      if (array_key_exists('entry_id', $params)) {
         $this->entry_id = $params['entry_id'];
      }
      
      if (array_key_exists('content', $params)) {
         $this->content = $params['content'];
      }
      
      if (array_key_exists('permalink', $params)) {
         $this->permalink = $params['permalink'];
      }
      
      if ((array_key_exists('permalink', $params)) &&
          (array_key_exists('blog_xid', $params))){
         $json = '{"permalinkUrl":"' . $params['permalink'] . '"}';
         $post_url = get_tpconnect_external_assets_api_url($params['blog_xid']);
         $events = post_json($post_url, $json);
         
         $this->blog_xid = $params['blog_xid'];
         $this->xid = $events->asset->urlId;
      }
      
      $this->tp_comment_listing = array();

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

      $escaped_content = urlencode($this->content);
      $params = "blog_xid=" . $this->blog_xid . "&" . 
               "permalink=" . $this->permalink . "&" . 
               "fb_id=" . FACEBOOK_POST_ID_PREFIX . $this->entry_id . "&" . 
               "content=" . $escaped_content . "&" . 
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
   function Entry($params) {
      if (!array_key_exists('xid', $params)) {
         debug ("[Entry::Entry] The entry XID is required in the constructor...");
         return;
      }

      if (!array_key_exists('json', $params)) {
            $params['json'] = pull_json(get_entry_api_url($params['xid']));
       }
       
       // otherwise, ($type == 'json'), format is ready to parse. 
       $this->title = get_entry_title($params['json']);
       $this->body = $params['json']->content;
       $this->permalink = $params['json']->permalinkUrl;
       $this->thumbnail = get_first_thumbnail($params['json']->embeddedImageLinks);
       $this->xid = $params['json']->urlId;
       
       $this->author = new Author(array('xid'  => $params['json']->author->urlId,
                                        'json' => $params['json']->author ));

       // GETTING RID OF DateTime for PHP 5.1.6 compatibility
//       $date =  new DateTime($entry_json->published);
//       $this->timestamp = print_timestamp($date);
      $this->timestamp = new TPDate($params['json']->published);
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
       $this->comment_listing = new TPCommentListing(array('xid' => $this->xid));
    }
    
    function build_favorite_listing() {
       $this->favorite_listing = new FavoriteListing(array('xid' => $this->xid));
    }
    
    function build_fb_comment_listing() {
       $this->fb_comment_listing = new FBCommentListing(FACEBOOK_POST_ID_PREFIX . $this->xid);
    }
    
    function build_braided_listing() {
       $this->braided_listing = new BraidedCommentListing($this->fb_comment_listing->comments(), 
                                                          $this->comment_listing->comments());
    }
    
    function rousseaus_listing() {                                                                                                           
       // Use the Rousseau server.

       $escaped_content = urlencode('<h3>'. $this->title . '</h3>');
       $params = "xid=" . $this->xid . "&" . 
                "permalink=" . $this->permalink . "&" . 
                "fb_prefix=" . FACEBOOK_POST_ID_PREFIX . "&" . 
                "content=" . $escaped_content . "&" . 
                "HTML=1";

       return rousseaus_comments($params);
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


 