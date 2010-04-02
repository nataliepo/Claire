<?php
 
   include_once ("oauth-php-98/library/OAuthStore.php");
   include_once ("oauth-php-98/library/OAuthRequester.php");

   include_once('config.php'); 
   
   $author = "";

  
   $db_options = array('server' => 'localhost',
                       'username' => 'rocky',
                       'password' => 'four',
                       'database' => 'oauth_test');



   $store   = OAuthStore::instance('MySQL', $db_options);     
   
   $user_id = get_user_id(COOKIE_NAME);
   debug ("USER ID = $user_id");
   
    
   $servers = $store->listServers('', $user_id);
   
   $response = array();
   foreach (array_keys($_GET) as $key) {
      $response[$key] = $_GET[$key];
   }
     
   // this gets the API urls again; they should probably be stored in the sql
   // table, but i'm not sure if they are right now -- requesting anyway!
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
             $endpoint_strs[$str] = $link->href;
          }
       }  
   }


   $r = "";
   $is_logged_in = 0;
   $oauth_token = get_oauth_token(COOKIE_NAME, $_GET, $store);

   debug ("OAUTH TOKEN = $oauth_token");
      
   if (array_key_exists('logout', $_GET)) {
      // Delete the server request token, this one was for one use only
      // $store->deleteServerToken($consumer_key, $r['token'], 0, true);
      // abstract public function deleteServerToken ( $consumer_key, $token, $user_id, $user_is_admin = false );

      debug ("Deleting this token from the consumer record...");
      $store->deleteConsumer(CONSUMER_KEY, $user_id);
   
      debug ("Deleting active session...");
      $is_logged_in = 0;
   
      debug ("Deleting cookie...");
      setcookie(COOKIE_NAME, '', time()-3600);
      
      // Clear the locally defined oauth_token to indicate to the rest of the procedures that the user
      // is NOT logged in.
      $oauth_token = 0;
   }


   // Just got back from the Access token request, so we need to verify...
   else if (array_key_exists('oauth_verifier', $_GET)) {
      debug ("[JUST GOT ACCESS TOKEN, ATTEMPTING TO VERIFY...]");
      try {
         $r = $store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 
                                            'request', $user_id);
      }
      catch (OAuthException2 $e) {
         var_dump($e);
         echo "<h2>[OAuth Exception 2]</h2>";
         
         // If we're catching an exception here, it's likely that a user is refreshing the page after
         // they've submitted once.  Try to use the DB-stored oauth token instead...
         try {
            $r = $store->getServerTokenSecrets(CONSUMER_KEY, get_oauth_token_from_db(COOKIE_NAME, $_GET, $store),
                                               'request', $user_id);
         }
         catch (OAuthException2 $e) {
            var_dump($e);
            debug ("Loading the Token Secrets off the token in the DB did not work.");
         }
      }


      // make a generic Request object, and then sign it with the secret token
      $oauth 	= new OAuthRequester($endpoint_strs['oauth-access-token-endpoint'], 
         'GET');
      $oauth->sign($user_id, $r);
 
      $final_url = $endpoint_strs['oauth-access-token-endpoint'] . "?";

      $parameters = array('timestamp', 'nonce', 'consumer_key', 
                          'version', 'signature_method', 'signature', 'token');

      foreach ($parameters as $parm) {
         $final_url .= 'oauth_' . $parm . '=' . $oauth->getParam('oauth_' . $parm) . '&';
      }  
 
      // don't forget the verifier!
      $final_url .= 'oauth_verifier=' . $_GET['oauth_verifier'];

      debug ("FINAL URL = $final_url");

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
      
      
      // Successful verification.
      if ($doc) {
      
         $response = array();    
         $response_array = explode("&", $doc);
         foreach ($response_array as $response_str) {
            $pair = explode("=", $response_str);
            $response[$pair[0]] = $pair[1];
         }   
         
//         // debug ("after stream_get_contents(), response = ^ " . var_dump($response));
         

         $r		    = $store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 'request', $user_id);
         $token_name	= $r['token_name'];
   		$opts         = array();
   		$opts['name'] = $token_name;
         $store->addServerToken(CONSUMER_KEY, 'access', 
                               $response['oauth_token'], $response['oauth_token_secret'], 
                               $user_id, $opts);                         
                            
         // also putting this in a cookie.
         setcookie(COOKIE_NAME, $user_id);
				
			// Clear what's in the URL.
         $oauth_token = get_oauth_token_from_db(COOKIE_NAME, $_GET, $store);
	   }
   }
	
   if ($oauth_token) {
      $is_logged_in = 1;
      
      $typepad_url = 'https://api.typepad.com/users/@self.json';
   //$typepad_url = 'https://api.typepad.com/users/6p00e5539faa3b8833.json';

      $header_string = 'OAuth realm="api.typepad.com", ';

      // Make a dummy OAuth Request object so we can use its signed parameters
      $oauth 	= new OAuthRequester($endpoint_strs['oauth-access-token-endpoint'], 
         'GET');
      // Grab the access secret_token
      
      $r = $store->getServerToken(CONSUMER_KEY, $oauth_token, $user_id);

      // this will croak on a string vs array object if we don't rewrite the current value
      $signature_methods = array('PLAINTEXT');
      $r['signature_methods'] = $signature_methods;
      
      $oauth->sign($user_id, $r);

      $parameters = array('timestamp', 'nonce', 'consumer_key', 
               'version', 'signature_method', 'signature');
      foreach ($parameters as $parm) {
         $header_string .= 'oauth_' . $parm . 
                  '="' . $oauth->getParam('oauth_' . $parm) . '", ';
      }
      
      $header_string .= 'oauth_token="' . $r['token'] . '"';
      
//      debug ("[is_logged_in] parameter string = $header_string");

      $header_array = array('Authorization:' . $header_string,
                           'Content-Type: application/json;'); 

      $ch = curl_init($typepad_url);   
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
      curl_setopt($ch, CURLOPT_HTTPHEADER,      $header_array);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION,  1);
      curl_setopt($ch, CURLOPT_HEADER,          false); 

      $response = json_decode(curl_exec($ch));
      curl_close($ch);

      $author = new Author(1, $response);
      
      debug ("[index.php] calling remember_author()");
      $oauth_user_id = remember_author($author);
      
      debug ("[final_request] This author's id is $oauth_user_id");
      // Also create a cookie out of this author.
      setcookie(COOKIE_NAME, $oauth_user_id);
      
      // If our cookie has a temporary author record...
      if (($_COOKIE[COOKIE_NAME] != $oauth_user_id) and
          ($oauth_user_id)){
         
         $old_oauth_id = $_COOKIE[COOKIE_NAME];
         
         debug ("Replacing temporary oauth_oauthor credentials...");
         // Update the OAuth table to user our author lookup.
         replace_oauth_author($old_oauth_id, $oauth_user_id);
         
         debug ("Setting active cookie...");
         // Correct your active cookie.
         setcookie(COOKIE_NAME, $oauth_user_id);
         
         debug ("Removing temporary author record...");
         // remove the temporary user.
         delete_author($old_oauth_id);
      }


      
   }
?>


<html>
<head>
   <title>TP Auth Example</title>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

</head>

<body>
   <h2>TypePad Loves You!</h2>

   <a class="next" href="index.php?logout=1">Log Out</a>

<?php
   
   if (!$author or
       !$is_logged_in) {      
      echo "<h3 align='center'><a href='alpha.php'>Log In</a>!</h3>";
   }
   else {
      echo "<h3 align='center'>Welcome, <a href='" . $author->profile_url . "'>" . 
            $author->display_name . "</a>!</h3>";
   }
?>
<h5>Known Users</h5>
<?php
   $users = get_users();
   
   if (sizeof($users)) {
      echo "<ul>";
      foreach ($users as $user) {
         // Checking if the xid is valid before I make an author record...
         $pattern = '/^(6p.+)$/';
         preg_match($pattern, $user['user_tp_xid'], $matches);

         if (sizeof($matches) > 0) {
            $author = new Author($user['user_tp_xid']);

//            echo "Name: " . $user['user_name'] . ", TP_XID = " . $user['user_tp_xid'] . 
//                     ", ID = " . $user['user_id'] . "</li>";
            echo "<li><img src='" . $author->avatar . "' /> " . 
                     "<a href='" . $author->profile_url . "'>". $author->display_name . "</a></li>";
         }
      }
      echo "</ul>";
   }
   else {
      echo "<p>There aren't any users in this system.</p>";
   }

?>


</body>
</html>
