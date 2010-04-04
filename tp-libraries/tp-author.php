<?php
   include_once('tp-utilities.php');
   
class Author {
    var $display_name;
    var $profile_url;
    var $avatar;
    var $xid;
    var $username;
    
//    function Author($xid = 0, $author_json = '') {
   function Author($params) {
      
      // Allow creationg of empty Author to allow
      // other services to override it.
      if ((!array_key_exists('xid', $params)) &&
          (!array_key_exists('json', $params)) &&
          (!array_key_exists('username', $params))) {
         return;
      }
       
      $identifier = array();
      
      // otherwise, remember the unique identifier for this user.
      if (array_key_exists('xid', $params)) {
         $identifier['value'] = $params['xid'];
         $identifier['source'] = 'xid';
      }
      else if (array_key_exists('json', $params)) {
         $identfier['value'] = $params['json']->urlId;
         $identifier['source'] = 'json';
      }
      else {
         $identifier['value'] = $params['username'];
         $identifier['source'] = 'username';
      }
       

      if (!array_key_exists('json', $params)) {
         $params['json'] = pull_json(get_author_api_url($identifier['value']));
      }

      // in case the API request couldn't find that user, return.
      if (!$params['json']) {
         debug ("[Author::Author] The user identified by " . $identifier['source'] . "'" . 
               $identifier['value'] . "' was not found.");
         return;
      }
      
      // At this point, we should have valid JSON.
       
       $this->xid = $params['json']->urlId;
       $this->display_name = $params['json']->displayName;
       $this->profile_url = $params['json']->profilePageUrl;
       $this->username = $params['json']->preferredUsername;
       $this->avatar = get_resized_avatar($params['json'], 35);
    }
}
    
?>