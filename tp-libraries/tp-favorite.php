<?php
    include_once('tp-utilities.php');
    

class FavoriteListing {
   var $favorite_array;
  
     
   // contructor -- expects 'xid' (of the post) as a parameter
   function FavoriteListing($params) {
      if (!array_key_exists('xid', $params)) {
         debug ("[FavoriteListing::FavoriteListing] Cannot create a FavoriteListing object without the Post XID as a parameter.");
         return;
      }
      
      $this->post_xid = $params['xid'];
      $this->favorite_listing = array();

      $events = pull_json(get_favorites_api_url($params['xid']));

      $i = 0;    

      foreach($events->{'entries'} as $favorite) {
         $this->favorite_array[$i] = new Favorite(array(xid  => $favorite->urlId, 
                                                        json => $favorite));
         $i++;
      }
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
//   function Favorite($xid, $favorite_json = '') {
   function Favorite($params) {
      if (!array_key_exists('xid', $params) and
          !array_key_exists('json', $params)) {
         debug ("[Favorite::Favorite] Favorite constructor requires an XID or JSON.");
         return;
      }
      
      if (!array_key_exists('json', $params)) {
         $params['json'] = pull_json(get_favorite_api_url($params['xid']));
      }

      $this->author = new Author(array(json => $params['json']->author));
                                       
      $this->xid = $params['json']->urlId;
      
/*     $date =  new DateTime($favorite_json->published);
      $this->timestamp = print_timestamp($date);
*/
      // FOR PHP 5.1.6 COMPATIBILITY.
      $this->timestamp = new TPDate($params['json']->published);
   }
   
   function time() {
      return $this->timestamp->print_readable_time();
   }
}