<?php
    include_once('tp-utilities.php');
    

class FavoriteListing {
   var $favorite_array;
  
   function build_favorite_listing ($xid) {
      $events = pull_json(get_favorites_api_url($xid));

      $i = 0;    

       foreach($events->{'entries'} as $favorite) {
           $this->favorite_array[$i] = new Favorite($favorite->urlId, $favorite);
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
   
   function size() {
      if (!$this->favorite_array) {
         return 0;
      }
      
      return sizeof($this->favorite_array);
   }
   
   function favorites() {
      if ($this->size() == 0) {
         return array();
      }
      
      return $this->favorite_array;
   }
} 
     
     
     
class Favorite {
   var $author;
   var $xid;
   var $timestamp;

   // contructor
   function Favorite($xid, $favorite_json = '') {
      if (!$favorite_json) {
         $favorite_json = pull_json(get_favorite_api_url($xid));
      }
      
      $this->author = new Author($favorite_json->author->urlId, $favorite_json->author);
      $this->xid = $favorite_json->urlId;
      
/*     $date =  new DateTime($favorite_json->published);
      $this->timestamp = print_timestamp($date);
*/
      // FOR PHP 5.1.6 COMPATIBILITY.
      $this->timestamp = new TPDate($favorite_json->published);
   }
   
   function time() {
      return $this->timestamp->print_readable_time();
   }
}