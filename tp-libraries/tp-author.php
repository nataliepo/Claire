<?php
class Author {
    var $display_name;
    var $profile_url;
    var $avatar;
    var $xid;
    
    function Author($xid = 0, $author_json = '') {

       // Allow creationg of empty Author to allow
       // other services to override it.
       if (!$xid) {
          return;
       }

       if (!$author_json) {
          $author_json = pull_json(get_author_api_url($xid));
       }
       
       $this->display_name = $author_json->displayName;
       $this->profile_url = $author_json->profilePageUrl;
       $this->avatar = get_resized_avatar($author_json, 35);
    }
}
    
?>