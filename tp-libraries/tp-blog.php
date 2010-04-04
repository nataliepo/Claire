<?php

   include_once('tp-utilities.php');

class EntryListing {
   var $entry_array; 
   var $page_number;
   var $posts_per_page;   
   var $total_entry_count;

   // contructor
   function EntryListing($params) {
      // XID is required.
      if (!array_key_exists('xid', $params)) {
         debug ("[EntryListing::EntryListing] Blog XID is required as an argument to the constructor.");
         return;
      }
         
      $this->entry_listing = array();
         
      if (!array_key_exists('page_number', $params)) {
         $params['page_number'] = 1;
      }
         
      if (!array_key_exists('posts_per_page', $params)) {
         $params['posts_per_page'] = POSTS_PER_PAGE;
      }
      
      $events = pull_json(get_entries_api_url($params));
         
      $i = 0;    
      foreach($events->{'entries'} as $entry) {
          $this->entry_array[$i] = new Entry(array('xid' => $entry->urlId, 
                                                   'json' => $entry));
          $i++;
      }
         
      $this->total_entry_count = $events->totalResults;
      $this->page_number = $params['page_number'];
      $this->posts_per_page = $params['posts_per_page'];
   }
   
   function has_next_page() {
      // calculate the number of pages
      $num_pages = floor($this->total_entry_count / $this->posts_per_page);
      
      // if there's a page with less than $posts_per_page, add one.
      if (($this->total_entry_count % $this->posts_per_page) != 0) {
         $num_pages += 1;
      }
      
      // knowing what page we're on, is it the last page
      if ($this->page_number == $num_pages) {
         return 0;
      }
      
      return 1;
   }
   
   function has_prev_page () {
      if ($this->page_number == 1) {
         return 0;
      }
      return 1;
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
?>
