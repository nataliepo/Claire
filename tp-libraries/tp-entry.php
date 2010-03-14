<?php
    include_once('tp-utilities.php');
    

    class EntryListing {
        var $entry_array; 
          
           function get_entries_api_url ($page_number) {
              
                return ROOT_TYPEPAD_API_URL . '/blogs/' . BLOG_XID . '/post-assets' . 
                    # This doesn't work for some reason.
                    #'/@published' . 
                    '.json' .'?max-results=' . POSTS_PER_PAGE . 
                    '&start-index=' . ((($page_number-1) * POSTS_PER_PAGE) + 1);

            }

            function build_entries_listing ($page_number) {
                $entries_url = $this->get_entries_api_url($page_number);
                                
                $handle = fopen($entries_url, "rb");
                $doc = stream_get_contents($handle);
                $events = json_decode($doc);

                $i = 0;    
                foreach($events->{'entries'} as $entry) {
                    $this->entry_array[$i] = new Entry($entry);
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
    



class Entry {
    var $title;
    var $body;
    var $permalink;
    var $thumbnail;
    
    // contructor
    function Entry($entry_json) {
        $this->title = get_entry_title($entry_json);
        $this->body = $entry_json->content;
        $this->permalink = $entry_json->permalinkUrl;
        $this->thumbnail = get_first_thumbnail($entry_json->embeddedImageLinks);
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
}
    
?>


 