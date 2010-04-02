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
   
   if ($GLOBALS['debug_mode']) {
      echo "<p class='request'>[PULL_JSON], URL = <a href='$url'>$url</a></p>";
   }

   $handle = fopen($url, "rb");

   $doc = stream_get_contents($handle);
   if ($decode) {
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


      return json_decode($doc);
      
   }
   else {
      return $doc;
   }
}

function post_text ($url, $params, $decode=1) {
   debug ("[POST_TEXT], URL = $url ");
   
   $ch = curl_init ($url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   
/*   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json;")); */
      
   $result = curl_exec($ch);
   if ($decode) {
      return json_decode($result);
   }
   else {
      return $result;
   }
}

function post_json ($url, $params) {
   if ($GLOBALS['debug_mode']) {
      echo "<p class='request'>[POST_JSON], URL = <a href='$url'>$url</a></p>";
   }

   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_HEADER, 0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Content-Type: application/json;"));

   debug ("[post_json] PHP VERSION = " . phpversion());
   
   return json_decode(curl_exec($ch));
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



/*****************
 * Useful methods...for Oauth
 *******************/
 
function print_as_table($array) {
    print "<table border='1'><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody>";
    foreach(array_keys($array) as $key) {
       print "<tr><td>$key</td><td>$array[$key]</td></tr>";
    }
    print "</tbody></table>";
 }

 function remember_author ($author) {
    // Check if this author exists first.
    debug ("[remember_author] this author's xid" . $author->xid);

    $id = get_id($author->xid);
    
    if ($id) {
       return $id;
    }

    $escaped_name = str_replace("'", "\'", $author->display_name);

    // otherwise, create a new record.
    $query = "INSERT INTO users (user_tp_xid, user_name) VALUES ('" . 
                $author->xid . "', '" . $escaped_name . "');";
             
    debug ("[remember_author] about to insert a new author with this query: $query");
   
    $result = mysql_query($query);

    // Now, get the author's id from the db.
    return get_id($author->xid);
 }

 function get_users() {
    $query = "SELECT * FROM users;";

    $result = mysql_query($query);
    $users = array();

//      $cols = array('id', 'tp_xid', 'oauth_id', 'name');
    $cols = array('id', 'tp_xid', 'name');

    for ($i = 0; $i < mysql_num_rows($result); $i++) {
       $this_user = array();
       foreach ($cols as $col) {
          $this_user['user_' . $col] = mysql_result($result, $i, "user_" . $col);
       }
       $users[] = $this_user;
    }

    return $users;
 }

 function get_id($xid) {
    $query = "SELECT * FROM users where user_tp_xid='$xid';";
    debug ("[get_id] Query: $query");
    $result = mysql_query($query);

    if (!$result ||
        !mysql_num_rows($result)) {
       return 0;
    }
    
    // otherwise, it exists
    return mysql_result($result, 0, "user_id");
 }
 

function get_user_id($cookie_name, $create_ifne=0) {
   $user_id = 0;
   if (array_key_exists($cookie_name, $_COOKIE)) {
      return $_COOKIE[$cookie_name];
   }
   
   if ($create_ifne) {
      $user_id =  create_temp_user();
      setcookie(COOKIE_NAME, $user_id);
   }
   
   return $user_id;
}

function create_temp_user() {
   // Make a temporary row.

   $rando = uniqid();
   $query = "INSERT INTO users (user_tp_xid, user_name) VALUES ('$rando', '');"; 

   debug ("[create_temp_user] Query = $query ");
   $result = mysql_query($query);
   
   if (!$result) {
      debug ("[create_temp_user] QUERY INSERT WENT BAD");
   }
   
   return get_id($rando);
}

function replace_oauth_author($old_author, $new_author) {
   // Update the token record first...
   $query = "update oauth_consumer_token set oct_usa_id_ref=$new_author where oct_usa_id_ref=$old_author;";
   $result = mysql_query($query);
   
   // You cannot have duplicate entries for ocr_usa_id_ref records, so delete any if they already exist.
   $query = "delete from oauth_consumer_registry where ocr_usa_id_ref=$new_author;";
   $result = mysql_query($query);
   
   // Then update the server registry record.
   $query = "update oauth_consumer_registry set ocr_usa_id_ref=$new_author where ocr_usa_id_ref=$old_author;";
   debug ("[replace_oauth_author] query = $query");
   $result = mysql_query($query);   
   
   // Finally, link the oauth_consumer_token record to the updated server registry record.
   $query = "select ocr_id from oauth_consumer_registry where ocr_usa_id_ref=$new_author;";
   $result = mysql_query($query);
   
   if ($result && mysql_num_rows($result)) {
      // otherwise, it exists
      $id = mysql_result($result, 0, "ocr_id");
      
      // update the oauth_consumer_token to be associated with this row's registry.
      $query = "update oauth_consumer_token set oct_ocr_id_ref=$id where oct_usa_id_ref=$new_author;";
      debug ("[replace_oauth_author], query = $query");
      $result = mysql_query($query);
   }
   else {
      debug ("[replace_oauth_author] There was an error with the query $query");
   }
}

function delete_author($id) {
   $query = "delete from users where user_id=$id;";
   $result = mysql_query($query);
}


function get_oauth_token($cookie_name, $params, $store) {
   // The OAuth token is in one of two places:
   $oauth_token = "";
   
   // 1. The URL parameter (as in, it's super new.)
   if (array_key_exists('oauth_token', $params)) {
      $oauth_token = $params['oauth_token'];
      // Make sure it's been written to the DB for this user.
      debug ("[get_oauth_token] The OAUth token was passed in the URL and = $oauth_token");
   }
   
   
   // 2. it resides in the DB.  key off of the user_id cookie.
   else if (array_key_exists($cookie_name, $_COOKIE)) {
      $oauth_token = get_oauth_token_from_db($cookie_name, $params, $store);
   }
   
   return $oauth_token;
}

function get_oauth_token_from_db($cookie_name, $params, $store) {
   $tokens = $store->listServerTokens($_COOKIE[$cookie_name]);

//    var_dump($tokens);
    debug("[get_oauth_token] all tokens = ^");
    if (sizeof($tokens) >= 1) {
       return $oauth_token = $tokens[0]['token'];
    }
    else {
       return 0;
    }
}

function verify_access_token  ($access_endpoint_url, $user_id, $store, $verifier) {

   $r		= $store->getServer(CONSUMER_KEY, $user_id);

   // make a generic Request object, and then sign it with the secret token
   $oauth 	= new OAuthRequester($access_endpoint_url, 'GET');
   
   debug("[verify_access_token] Verify endpoint url = $access_endpoint_url");
   
//   var_dump($r);
   debug("[verify_access_token] r = ^");
     
   try {
      $oauth->sign($user_id, $r);
   }
   catch (OAuthException2 $e) {
      debug ("No server tokens available for this URL parameter; you should sign in!");
      debug ("error = $e");
   }
     
   
   $final_url = $access_endpoint_url . "?";

   $parameters = array('timestamp', 'nonce', 'consumer_key', 
                         'version', 'signature_method', 'signature', 'token');

   foreach ($parameters as $parm) {
      $final_url .= 'oauth_' . $parm . '=' . $oauth->getParam('oauth_' . $parm) . '&';
   }  
   
   // don't forget the verifier!
   $final_url .= 'oauth_verifier=' . $verifier;
   
   debug ("[verify_access_token] FINAL URL: $final_url");
   

   $handle = fopen($final_url, "rb");
   return stream_get_contents($handle);
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

// Required for Facebook Commenting.
/*include_once ('fb-std-libraries/includes/facebook.php'); */



?>