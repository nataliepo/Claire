<?php
    include_once('tp-utilities.php');


class TPSession {
   var $endpoint_strs;
   var $store;
   var $oauth_token;
   var $user_id;
   var $author;
   var $api_endpoints;
    
   function is_logged_in() {
      if ($this->oauth_token) {
         return 1;
      }

      return 0;
   }

   // 
   function TPSession () {
      // define the DB store.
      if (!$this->store) {
         $this->store   = OAuthStore::instance('MySQL', get_db_options());     
      }

      // determine who this user is (from this site's cookie alone)
      $this->user_id = get_user_id(COOKIE_NAME);

//      debug ("This user's cookie is " . $this->user_id);
      
      // If there's no user_id in the cookie, then there's no session -- not logged in.
      if (!$this->user_id) {
         return 0;
      }

      // This method look up the OAuth token in one of two ways:
      //   1. the _GET parameters -- if this is the last step of the OAuth dance.
      //   2. the Database -- if the user already completed the OAuth dance.
      $this->oauth_token = get_oauth_token(COOKIE_NAME, $_GET, $this->store);

//      debug ("OAUTH TOKEN = " . $this->oauth_token);

      // Somebody wanted to log out!  You should let them.
      if (array_key_exists('logout', $_GET)) {
         $this->log_out();
      }

      // Just got back from the Access token request, so we need to make one final call
      // to verify it.  This call will also update $this->oauth_token.
      else if (array_key_exists('oauth_verifier', $_GET)) {
         $this->verify_access_token();
      }
      
      // Also update the local author record if all goes well...
      if (!$this->author and $this->is_logged_in()) {
         $this->update_author_record();
      }
   } 
   
   function make_authorized_request($url, $params="") {
      // Make a dummy OAuth Request object so we can use its signed parameters
      $oauth 	= new OAuthRequester($this->get_api_endpoint('oauth-access-token-endpoint'), 'GET');

      // Grab the access secret_token
      $r = $this->store->getServerToken(CONSUMER_KEY, $this->oauth_token, $this->user_id);

      // this will croak on a string vs array object if we don't rewrite the current value
      $signature_methods = array('PLAINTEXT');
      $r['signature_methods'] = $signature_methods;

      $oauth->sign($this->user_id, $r);

      $parameters = array('timestamp', 'nonce', 'consumer_key', 
                          'version', 'signature_method', 'signature');
      
      // Build the Authorization value, starting with the realm.                    
      $header_string = 'OAuth realm="api.typepad.com", ';

      // Then append each value in the $parameters array...
      foreach ($parameters as $parm) {
         $header_string .= 'oauth_' . $parm . '="' . $oauth->getParam('oauth_' . $parm) . '", ';
      }

      // ...ending with the access token from the DB.
      $header_string .= 'oauth_token="' . $r['token'] . '"';

      // Package up the Authorization value as an array, along with the expected Content-Type
      $header_array = array('Authorization:' . $header_string,
                            'Content-Type: application/json;'); 
                            
//      debug ("[update_author_record] Making the request at url = $url with Authorization: $header_string");

      $ch = curl_init($url);   
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
      curl_setopt($ch, CURLOPT_HTTPHEADER,      $header_array);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,  1);
      curl_setopt($ch, CURLOPT_HEADER,          false); 
      if ($params) {
         curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      }

      $response = claire_json_decode(curl_exec($ch));
      curl_close($ch);      
      
      return $response;
   }
    
   function update_author_record() {

      // Throw the extra 1 as a parameter since this is an authorized request.
      $typepad_url = get_author_api_url ('@self', 1);
      
      $response = $this->make_authorized_request($typepad_url);

      $this->author = new Author(array('json' => $response));

      // this writes the Author record to the db.
      $oauth_user_id = remember_author($this->author);

//      debug ("[final_request] This author's id is $oauth_user_id");

      // Also create a cookie out of this author.
      setcookie(COOKIE_NAME, $oauth_user_id);

      // When you begin the sign-on process, you're given a temporary user record
      // without its TypePad XID -- even if you already existed.  This block
      // makes the temporary request/access token your actual request/access tokens.
      if (($_COOKIE[COOKIE_NAME] != $oauth_user_id) && ($oauth_user_id)){
            
         // store the temporary user_id
         $old_oauth_id = $_COOKIE[COOKIE_NAME];

         debug ("Replacing temporary oauth_oauthor credentials...");

         // Update the OAuth table to user our author lookup.
         replace_oauth_author($old_oauth_id, $oauth_user_id);

         // Correct your active cookie.
         debug ("Setting active cookie...");
         setcookie(COOKIE_NAME, $oauth_user_id);

         // Remove the temporary user.
         debug ("Removing temporary author record...");
         delete_author($old_oauth_id);
      } 
   }
   
   function get_api_endpoint($endpoint) {
       if (!$this->store) {
          return "";
       }
       
       if (!$this->api_endpoints) {
          // this should be replaced with a db lookup...
          $url = 'http://api.typepad.com/api-keys/' . CONSUMER_KEY . '.json';      
          $handle = fopen($url, "rb");
          $doc = claire_json_decode(stream_get_contents($handle));

          $owner = $doc->owner;

          $endpoint_strs = array();
          $endpoint_strs['oauth-request-token-endpoint'] = "0";
          $endpoint_strs['oauth-authorization-page'] = "0";
          $endpoint_strs['oauth-access-token-endpoint'] = "0";

          foreach ($doc->owner->links as $link) {
             foreach (array_keys($endpoint_strs) as $str) {
                if ($link->rel == $str) {
                   $this->api_endpoints[$str] = $link->href;
                }
             }  
          }
       }
       
       return $this->api_endpoints[$endpoint];
   }
    
    
   function verify_access_token() {
      debug ("[JUST GOT ACCESS TOKEN, ATTEMPTING TO VERIFY...]");
      
      try {
         $r = $this->store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 
                                                   'request', $this->user_id);
      }
      catch (OAuthException2 $e) {
         var_dump($e);
         echo "<h2>[OAuth Exception 2]</h2>";

         // If we're catching an exception here, it's likely that a user is refreshing the page after
         // they've submitted once.  Try to use the DB-stored oauth token instead...
         try {
            $r = $this->store->getServerTokenSecrets(CONSUMER_KEY, get_oauth_token_from_db(COOKIE_NAME, $_GET, $this->store),
                                                   'request', $this->user_id);
         }
         catch (OAuthException2 $e) {
            var_dump($e);
            debug ("Loading the Token Secrets off the token in the DB did not work.");
         }
      }

      // make a generic Request object, and then sign it with the secret token
      $oauth 	= new OAuthRequester($this->get_api_endpoint('oauth-access-token-endpoint'),'GET');
      $oauth->sign($this->user_id, $r);

      $final_url = $this->get_api_endpoint('oauth-access-token-endpoint') . "?";

      $parameters = array('timestamp', 'nonce', 'consumer_key', 
                           'version', 'signature_method', 'signature', 'token');

      foreach ($parameters as $parm) {
         $final_url .= 'oauth_' . $parm . '=' . $oauth->getParam('oauth_' . $parm) . '&';
      }  

      // don't forget the verifier!
      $final_url .= 'oauth_verifier=' . $_GET['oauth_verifier'];

//      debug ("FINAL URL = $final_url");

      /********
      * THIS GENERATES ERRORS IN PHP 5.1.X
      *      $handle = fopen($final_url, "rb");
      *      $doc = stream_get_contents($handle);
      *******/

      /********
      * THIS GENERATES ERRORS locally. probably doesn't work.
      $ch = curl_init($final_url);   
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,  1);
      curl_setopt($ch, CURLOPT_HEADER,          false); 

      $doc = json_decode(curl_exec($ch));
      curl_close($ch);
      *******/

      $handle = fopen($final_url, "rb");
      $doc = stream_get_contents($handle);
      fclose($handle);

      // Successful verification.
      if ($doc) {
         $response = array();    
         $response_array = explode("&", $doc);
         foreach ($response_array as $response_str) {
            $pair = explode("=", $response_str);
            $response[$pair[0]] = $pair[1];
         }    

         $r		    = $this->store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 
                                                         'request', $this->user_id);
         $token_name	= $r['token_name'];
      	$opts         = array();
      	$opts['name'] = $token_name;
         $this->store->addServerToken(CONSUMER_KEY, 'access', 
                                      $response['oauth_token'], $response['oauth_token_secret'], 
                                      $this->user_id, $opts);                         

         // also putting this in a cookie.
         setcookie(COOKIE_NAME, $this->user_id);

      	// Ignore what's in the URL -- use what's in the DB.
         $this->oauth_token = get_oauth_token_from_db(COOKIE_NAME, $_GET, $this->store);
      }
      else {
         $this->oauth_token = "";
      }       
   }
   
   function log_out() {
      if ($this->user_id) {
         debug ("Deleting this token from the consumer record...");
         $this->store->deleteConsumer(CONSUMER_KEY, $this->user_id);
      }
      
      debug ("Deleting this user's cookie from this site...");
      setcookie(COOKIE_NAME, '', time()-3600);

      // Clear the locally defined oauth_token to indicate to the rest of the procedures that the user
      // is NOT logged in.
      $this->oauth_token = "";
   }
   
   // This is a wrapper method for the first Login page of the OAuth process, and does the following:
   //  1. Formulates a request to TypePad for a Request Token 
   //  2. Makes the request to TypePad
   //  3. Parses the request result, makes sure everything's okay.
   // It does NOT redirect to TypePad for login. 
   function request_and_verify_request_token() {

      // If there exists any active session, destroy it for simplicity's sake.
      $this->log_out();
      
      // create a temp user and make a cookie for his record
      $this->user_id = create_temp_user();
      setcookie(COOKIE_NAME, $this->user_id);

      // At this point, we shouldn't have anything in the DB with a record of this transaction.
      // Set up the required parameters to recognize an OAuth provider -- known in this OAuthPHP lib as
      // a record in the oauth_consumer_registry table.

      $server = array('consumer_key'      => CONSUMER_KEY, 
                      'consumer_secret'   => CONSUMER_SECRET,
                      'server_uri'        => ROOT_TYPEPAD_API_URL,
                      'signature_methods' => array('PLAINTEXT'),
                      'request_token_uri' => $this->get_api_endpoint('oauth-request-token-endpoint'),
                      'authorize_uri'     => $this->get_api_endpoint('oauth-authorization-page'),
                      'access_token_uri'  => $this->get_api_endpoint('oauth-access-token-endpoint')
                  //  'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),                         
                  //  'signature_methods' => array('MD5', 'PLAINTEXT'),
                  //  ** This one is not implemented yet in this PHP library.
                  //  'signature_methods' => array('RSA-SHA1', 'PLAINTEXT'),
                     );

      // See which known services exist for this user 
      $servers = $this->store->listServers('', $this->user_id);
      
      // Refresh the known OAuth providers for this user by deleting them if they already exist...
      foreach ($servers as $server_item) {
         if (($server_item['consumer_key'] == CONSUMER_KEY) &&
             ($server_item['user_id'] == $this->user_id)) {
//            debug ("User_id = " . $this->user_id);
            $this->store->deleteServer(CONSUMER_KEY, $this->user_id);
         }
      }

      // otherwise, create a new record of this OAuth provider.
      $consumer_key = $this->store->updateServer($server, $this->user_id);

      /*
         * These methods from this OAuth PHP lib don't create the right type of GET request...

            $options = array();
            $options[CURLOPT_HTTPHEADER] = $server;
            $token = OAuthRequester::requestRequestToken(CONSUMER_KEY, $user_id); //, '', 'GET', $options);
            $token = OAuthRequester::requestRequestToken(CONSUMER_KEY, $user_id, '', 'GET');
      */

      $r		= $this->store->getServer(CONSUMER_KEY, $this->user_id);

      // This creates a generic Request object, so we'll have to fill in the rest...
      $oauth 	= new OAuthRequester($this->get_api_endpoint('oauth-request-token-endpoint'), '', '');
      $oauth->setParam('oauth_callback', CALLBACK_URL);

      // ..and this adds more parameters, like the timestamp, nonce, version, signature method, etc
      $oauth->sign($this->user_id, $r);

      // Begin to build the URL string with the request token endpoint
      $final_url = $this->get_api_endpoint('oauth-request-token-endpoint') . "?";

      $parameters = array('timestamp', 'callback', 'nonce', 'consumer_key', 
                          'version', 'signature_method', 'signature');
      
      foreach ($parameters as $parm) {
         $final_url .= 'oauth_' . $parm . '=' . $oauth->getParam('oauth_' . $parm) . '&';
      }

      /* Now execute the long query that may look something like this:

            https://www.typepad.com/secure/services/oauth/request_token ?
               oauth_signature=n3lQROBcPnBZvEgplUzHcgkUCrA%3D &
               oauth_timestamp=1269811986 &
               oauth_callback=http%3A%2F%2F127.0.0.1%3A5000%2Flogin-callback &
               oauth_nonce=853433351 &
               oauth_consumer_key=c5139cef2985b86d &
               oauth_version=1.0 &
               oauth_signature_method=HMAC-SHA1
      */
            
//      debug ("Final Url = $final_url");
      // and go ahead and execute the request.
      $handle = fopen($final_url, "rb");
      $doc = stream_get_contents($handle);     
      $response_array = explode("&", $doc);

//      debug ("Response from request = ^" . var_dump($response_array));
      
      // TODO: Verbose error handling

      // Store the results!  
      $response = array();
      foreach ($response_array as $response_str) {
         $pair = explode("=", $response_str);
         $response[$pair[0]] = $pair[1];
      }

      // Instead of storing the Request token as a cookie, write it to the db.
      $this->store->addServerToken(CONSUMER_KEY, 'request', $response['oauth_token'], 
                                          $response['oauth_token_secret'], $this->user_id, '');

      var_dump($oauth);
//      debug ("After creating a simple request token, store obj = ^ ");      
      
      $this->oauth_token = $response['oauth_token'];     
   }
   
}

function get_db_options() {
   return array('server'     => DB_HOST,
                       'username'   => DB_USERNAME,
                       'password'   => DB_PASSWORD,
                       'database'   => DB_NAME);
}
   

function get_oauth_token($cookie_name, $params, $store) {
   // The OAuth token is in one of two places:
   $oauth_token = "";
   
   // 1. The URL parameter (as in, it's super new.)
   if (array_key_exists('oauth_token', $params)) {
      $oauth_token = $params['oauth_token'];
      // Make sure it's been written to the DB for this user.
   }
   
   // 2. it resides in the DB.  key off of the user_id cookie.
   else if (array_key_exists($cookie_name, $_COOKIE)) {
      $oauth_token = get_oauth_token_from_db($cookie_name, $params, $store);
   }
   
   return $oauth_token;
}

function get_oauth_token_from_db($cookie_name, $params, $store) {
   $tokens = $store->listServerTokens($_COOKIE[$cookie_name]);

    if (sizeof($tokens) >= 1) {
       return $oauth_token = $tokens[0]['token'];
    }
    else {
       return 0;
    }
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
   $result = mysql_query($query);   
   
   // Finally, link the oauth_consumer_token record to the updated server registry record.
   $query = "select ocr_id from oauth_consumer_registry where ocr_usa_id_ref=$new_author;";
   $result = mysql_query($query);
   
   if ($result && mysql_num_rows($result)) {
      // otherwise, it exists
      $id = mysql_result($result, 0, "ocr_id");
      
      // update the oauth_consumer_token to be associated with this row's registry.
      $query = "update oauth_consumer_token set oct_ocr_id_ref=$id where oct_usa_id_ref=$new_author;";
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

function get_id($xid) {
    $query = "SELECT * FROM users where user_tp_xid='$xid';";
    $result = mysql_query($query);

    if (!$result ||
        !mysql_num_rows($result)) {
       return 0;
    }
    
    // otherwise, it exists
    return mysql_result($result, 0, "user_id");
}

function get_local_users_list() {
    $query = "SELECT * FROM users;";

    $result = mysql_query($query);
    $users = array();

//      $cols = array('id', 'tp_xid', 'oauth_id', 'name');
    $cols = array('id', 'tp_xid', 'name');

    if (!$result) {
       return array();
    }
    
    for ($i = 0; $i < mysql_num_rows($result); $i++) {
       $this_user = array();
       foreach ($cols as $col) {
          $this_user['user_' . $col] = mysql_result($result, $i, "user_" . $col);
       }
       $users[] = $this_user;
    }

    return $users;
}

function remember_author ($author) {
    // check if the author exists.
    $id = get_id($author->xid);
    
    if ($id) {
       return $id;
    }

    $escaped_name = str_replace("'", "\'", $author->display_name);

    // otherwise, create a new record.
    $query = "INSERT INTO users (user_tp_xid, user_name) VALUES ('" . 
                $author->xid . "', '" . $escaped_name . "');";
   
    $result = mysql_query($query);

    // Now, get the author's id from the db.
    return get_id($author->xid);
}


?>