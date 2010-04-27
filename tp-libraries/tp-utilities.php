<?php

    include_once('tp-config.php');
    include_once('json_lib.php');

    // default set in config.
    global $debug_mode;
    $debug_mode = DEFAULT_DEBUG_MODE;
    
/*****
 * utility features mostly borrowed from Tydget's typepad_parsing.js
 *****/
function get_resized_avatar ($user, $size) {
    // use the lilypad as a default in case all else fails
    $default_avatar = 'http://up3.typepad.com/6a00d83451c82369e20120a4e574c1970b-50si';
    
    /*
     *  The links arrays were deprecated in R51 (04/2010)
    $links_array = $user->links;
    foreach ($links_array as $link) {
        if ($link->rel == "avatar") {
            if ($link->width < 125) {
                return $link->href;
            } 
        }
    }
    */
    if ($user->avatarLink) {
       return $user->avatarLink->url;
    }

   return $default_avatar;
}

function get_entry_title($entry) {
   if ($entry->title) {
//    if ($entry->title) {
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

function get_entries_api_url ($params) {
    return ROOT_TYPEPAD_API_URL . '/blogs/' . $params['xid'] . '/post-assets' . 
        '.json' .'?max-results=' . $params['posts_per_page'] . 
        '&start-index=' . ((($params['page_number']-1) * $params['posts_per_page']) + 1);
}

function get_entry_api_url ($xid) {
   return ROOT_TYPEPAD_API_URL . '/assets/' . $xid . '.json';
}

function get_blog_settings_api_url($xid) {
   return ROOT_TYPEPAD_API_URL . '/blogs/' . $xid . '/commenting-settings.json';
}

function get_comments_api_url ($xid, $is_auth=0) {
   $root = "";
    if ($is_auth) {
       $root = ROOT_TYPEPAD_AUTH_API_URL;
    }
    else {
       $root = ROOT_TYPEPAD_API_URL;
    }   
    
    return $root . '/assets/' . $xid . '/comments.json';
}

function get_favorites_api_url ($xid) {
     return ROOT_TYPEPAD_API_URL . '/assets/' . $xid . '/favorites.json';
}

function get_author_api_url ($xid, $is_auth=0) {
   $root = "";
   if ($is_auth) {
      $root = ROOT_TYPEPAD_AUTH_API_URL;
   }
   else {
      $root = ROOT_TYPEPAD_API_URL;
   }
   
   return $root . '/users/' . $xid . '.json';
}

function get_tpconnect_external_assets_api_url($xid) {
   return ROOT_TYPEPAD_API_URL . '/blogs/' . $xid . '/discover-external-post-asset.json';
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
function pull_json ($url, $decode=1) { 
   
   debug("<p class='request'>[PULL_JSON], URL = <a href='$url'>$url</a></p>");

   $ch = curl_init ($url);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($ch);
   
   if ($decode) {
      return claire_json_decode($result);
   }
   else {
      return $result;
   }
}

function post_text ($url, $params, $decode=1) {
   debug ("[POST_TEXT], URL = $url , params = $params");
   
   $ch = curl_init ($url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json;")); 
      
   $result = curl_exec($ch);

   if ($decode) {
      return json_decode($result);
   }
   else {
      return $result;
   }
}

function post_json ($url, $params) {
   debug("[POST_JSON], URL = <a href='$url'>$url</a>");

   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json;"));
   
   return claire_json_decode(curl_exec($ch));
}

function debug ($msg) {
   if (DEFAULT_DEBUG_MODE) {
      echo '<p class="debug">' . $msg . '</p>';
   }
}

function print_tp_timestamp ($datetime) {
   return $datetime->format('F d, Y g:ia');
}

function print_timestamp_from_epoch ($time) {
   return date("m d, Y g:ia", $time); 
}


 
function print_as_table($array) {
    print "<table border='1'><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody>";
    foreach(array_keys($array) as $key) {
       print "<tr><td>$key</td><td>$array[$key]</td></tr>";
    }
    print "</tbody></table>";
}



function claire_json_decode($str) {

   if (!function_exists('json_decode')) {

      function json_decode($doc, $assoc=false) {
   //            require_once 'classes/JSON.php';
         if ($assoc) {
            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
         }
         else {
            $json = new Services_JSON;
         }
            
         $result =  $json->decode($doc);
         return $result;
      }
   }
   
   return json_decode($str);
}


/*****************************
 * REQUIRED INCLUDES
 *****************************/
include_once('tp-comment.php');
include_once('tp-entry.php');
include_once('tp-favorite.php');
include_once('tp-author.php');
include_once('tp-date.php');
include_once('tp-blog.php');
include_once('tp-oauth.php');

// Required for Facebook Commenting.
/*include_once ('fb-std-libraries/includes/facebook.php'); */



?>