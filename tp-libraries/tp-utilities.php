<?php

    include_once('tp-config.php');

    // default set in config.
    global $debug_mode;
    $debug_mode = DEFAULT_DEBUG_MODE;
    
/*****
 * utility features mostly borrowed from Tydget's typepad_parsing.js
 *****/
function get_resized_avatar ($user, $size) {
    // use the lilypad as a default in case all else fails
    $default_avatar = 'http://up3.typepad.com/6a00d83451c82369e20120a4e574c1970b-50si';
    
    $links_array = $user->links;
    foreach ($links_array as $link) {
        if ($link->rel == "avatar") {
            if ($link->width < 125) {
                return $link->href;
            } 
        }
    }

   return $default_avatar;
}

function get_entry_title($entry) {
    if ($entry->title) {
        return $entry->title;
    }
    else {
        return "Untitled Entry";
    }
}



function chop_str ($str, $size) {
    
    // Cut out the HTML/PHP from the entry body.
    
   $str = strip_tags($str);
   if (strlen($str) <= $size) {
      return $str;
   }
   
   $str_parts = array();
   $str_parts = explode(" ", $str);
   
   // now we have an array of words.
   $i = 0;

   $curr = "";
   $next = $str_parts[$i];
   while (strlen($next) < $size) {
      $curr .= $str_parts[$i] . ' ';
      $i++;
      $next .= $str_parts[$i] . ' ';
   }
  
   // chop the last space
   $curr = substr($curr, 0, (strlen($curr) - 1));
   return $curr . "...";
}

/** may want to provide a default thumbnail here...something in tp-config, perhaps. **/
function get_first_thumbnail ($embedded_array) {
    if (!$embedded_array) {
        return "";
    }
    
    return $embedded_array[0]->url;
}

function get_entries_api_url ($page_number) {
    return ROOT_TYPEPAD_API_URL . '/blogs/' . BLOG_XID . '/post-assets' . 
        # This doesn't work for some reason.
        #'/@published' . 
        '.json' .'?max-results=' . POSTS_PER_PAGE . 
        '&start-index=' . ((($page_number-1) * POSTS_PER_PAGE) + 1);
}

function get_entry_api_url ($xid) {
   return ROOT_TYPEPAD_API_URL . '/assets/' . $xid . '.json';
}

function get_comments_api_url ($xid) {
     return ROOT_TYPEPAD_API_URL . '/assets/' . $xid . '/comments.json';
}

function get_favorites_api_url ($xid) {
     return ROOT_TYPEPAD_API_URL . '/assets/' . $xid . '/favorites.json';
}

function get_author_api_url ($xid) {
   return ROOT_TYPEPAD_API_URL . '/users/' . $xid . '.json';
}

function get_comment_api_url ($xid) {  
   /*
    * this is an unverified api call -- 
    * constructing it from other models and info on 
    * http://www.typepad.com/services/apidocs/objecttypes/Comment
    */
//   return ROOT_TYPEPAD_API_URL . '/comment/' . $xid . '.json';
}

function get_favorite_api_url ($xid) {
   /*
    * this is an unverified api call -- 
    * constructing it from other models and info on 
    * http://www.typepad.com/services/apidocs/objecttypes/Favorite
    */
  //  return ROOT_TYPEPAD_API_URL . '/comment/' . $xid . '.json';
    
}

/** 
 *   input: 
 *    $URL -- a full string to scrape the json data from
 *  output: 
 *    a json-decoded php object.
**/
function pull_json ($url) { 
   
   if ($GLOBALS['debug_mode']) {
      echo "<p class='debug'>[PULL_JSON], URL = <a href='$url'>$url</a></p>";
   }
   $handle = fopen($url, "rb");
   $doc = stream_get_contents($handle);
   return json_decode($doc);
}

function debug ($msg) {
   if (DEFAULT_DEBUG_MODE) {
      echo '<p class="debug">' . $msg . '</p>';
   }
}

include_once('tp-comment.php');
include_once('tp-entry.php');
include_once('tp-favorite.php');
include_once('tp-author.php');
include_once ('fb-std-libraries/includes/facebook.php');


?>