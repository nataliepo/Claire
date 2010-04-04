<?php
      
      include_once ("oauth-php-98/library/OAuthStore.php");
      include_once ("oauth-php-98/library/OAuthRequester.php");
      
      include_once('config.php'); 
      
      $db_options = array('server'     => DB_HOST,
                          'username'   => DB_USERNAME,
                          'password'   => DB_PASSWORD,
                          'database'   => DB_NAME);

      $store   = OAuthStore::instance('MySQL', $db_options);      
      
      /* There should be nothing written to the DB at this point. */

      /* 
         Then, make the request to TypePad to get the necessary OAuth URlS. 
      */
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
      /*
         Again, still nothing written to the DB at this point.
      */
      
      $server = array('consumer_key' => CONSUMER_KEY, 
                       'consumer_secret' => CONSUMER_SECRET,
                       'server_uri' => 'http://api.typepad.com/',
//                       'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
                         'signature_methods' => array('PLAINTEXT'),
//                       'signature_methods' => array('MD5', 'PLAINTEXT'),
//                ** This one is not implemented yet in this PHP library.
//                       'signature_methods' => array('RSA-SHA1', 'PLAINTEXT'),
                       'request_token_uri' => $endpoint_strs['oauth-request-token-endpoint'],
                       'authorize_uri' => $endpoint_strs['oauth-authorization-page'],
                       'access_token_uri' => $endpoint_strs['oauth-access-token-endpoint']
                      );

//     $user_id = 1;   
   $user_id = get_user_id(COOKIE_NAME, 1);

     /* 
    
           This adds a row to the following tables:
              oauth_consumer_registry

      Once you write this row to the DB, you can't write it again.
     * I wish the PHP OAuth code would not error out but just move on...
     */
     $servers = $store->listServers('', $user_id);
     
     debug ("User ID here  = $user_id");
     debug ("just called list servers and response = ^" . var_dump($servers));
     

     foreach ($servers as $server_item) {
        
        if (($server_item['consumer_key'] == CONSUMER_KEY) &&
            ($server_item['user_id'] == $user_id)) {
            debug ("User_id = $user_id, ");
            $store->deleteServer(CONSUMER_KEY, $user_id);
         }
      }
      
      $consumer_key = $store->updateServer($server, $user_id);
     

/*
   * These don't create the right type of GET request.

      $options = array();
      $options[CURLOPT_HTTPHEADER] = $server;
      $token = OAuthRequester::requestRequestToken(CONSUMER_KEY, $user_id); //, '', 'GET', $options);
      $token = OAuthRequester::requestRequestToken(CONSUMER_KEY, $user_id, '', 'GET');
*/
   
      $r		= $store->getServer(CONSUMER_KEY, $user_id);

		
		// This creates a generic Request object.
      $oauth 	= new OAuthRequester($endpoint_strs['oauth-request-token-endpoint'], '', '');
		$oauth->setParam('oauth_callback', CALLBACK_URL);

      // ..and this adds more parameters, like the timestamp, nonce, version, signature method, etc
      $oauth->sign($user_id, $r);
      
      $final_url = $endpoint_strs['oauth-request-token-endpoint'] . "?";

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
      $handle = fopen($final_url, "rb");
      $doc = stream_get_contents($handle);     
      $response_array = explode("&", $doc);
   
      // Store the results!  Anything but 200 will croak to the browser for now.
      $response = array();
      foreach ($response_array as $response_str) {
         $pair = explode("=", $response_str);
         $response[$pair[0]] = $pair[1];
      }

      // Instead of storing the Request token as a cookie, write it to the db.
      $store->addServerToken(CONSUMER_KEY, 'request', $response['oauth_token'], 
                             $response['oauth_token_secret'], $user_id, '');
                             
     var_dump($oauth);
     debug ("After creating a simple request token, store obj = ^ ");
	
   /*
      Next step in the OAuth Dance: Redirect your user to the Provider.
   */
   
   $query_params = array();
   $query_params['oauth_token'] = $response['oauth_token'];

   // the redirect() method is from the OAuth library
   OAuthRequest::redirect($endpoint_strs['oauth-authorization-page'], $query_params);
   
?><?php

// HTML is forbidden in this file, since the redirect's headers cannot be written!!

?>   