<?php
    include_once('tp-utilities.php');
    

class FavoriteListing {
   var $favorite_array;
  
   function build_favorite_listing ($xid) {
      $events = pull_json(get_favorites_api_url($xid));

      $i = 0;    

       foreach($events->{'entries'} as $favorite) {
           $this->favorite_array[$i] = new Favorite($favorite);
           $i++;
       }
      return;
   }  
     
   // contructor
   function FavoriteListing($post_xid = "") {
      $this->post_xid = $post_xid;
      $this->favorite_listing = array();
      $this->build_favorite_listing($post_xid);
   }    
   
   function favorites() {
       return $this->favorite_array;
   }
} 
     
     
     
class Favorite {
   var $author_avatar;
   var $author_display_name;
   var $author_profile_page_url;
   var $entry_xid;

   // contructor
   function Favorite($favorite_json) {
       $this->author_avatar = get_resized_avatar($favorite_json->author, 35);
       $this->author_display_name = $favorite_json->author->displayName;
       $this->author_profile_page_url = $favorite_json->author->profilePageUrl;
       $this->entry_xid = $favorite_json->urlId;
//       $this->author_profile_page_url = $comment_json->author->profilePageUrl;
//       $this->content = $comment_json->content;
   }
}