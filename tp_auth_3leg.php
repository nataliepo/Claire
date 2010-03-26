<html>
<head>
   <title>TP Auth Example</title>
   
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
   <?php
      define ('CONSUMER_KEY', 'c5139cef2985b86d');
      
      include_once ("oauth-php-98/library/OAuthStore.php");
      include_once ("oauth-php-98/library/OAuthRequester.php");
      
   
      $db_options = array('server' => 'localhost',
                          'username' => 'rocky',
                          'password' => 'four',
                          'database' => 'oauth_test');

      $store   = OAuthStore::instance('MySQL', $db_options);      

      
      // First, make the request to TypePad to get the necessary OAuth URlS.
      $url = 'http://api.typepad.com/api-keys/' . CONSUMER_KEY . '.json';      
      $handle = fopen($url, "rb");
      $doc = json_decode(stream_get_contents($handle));
                  
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
      
      $server = array('consumer_key' => CONSUMER_KEY, 
                       'consumer_secret' => 'K0J0Im71',
                       'server_uri' => 'http://api.typepad.com/',
                       'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
                       'request_token_uri' => $endpoint_strs['oauth-request-token-endpoint'],
                       'authorize_uri' => $endpoint_strs['oauth-authorization-page'],
                       'access_token_uri' => $endpoint_strs['oauth-access-token-endpoint']
                      );
                      
      var_dump ($server);
      echo "<br /><br />";

     $user_id = 1;   
     $consumer_key = $store->updateServer($server, $user_id);
     
     $token = OAuthRequster::requestRequestToken(CONSUMER_KEY, $user_id);
                   



/* 
                       'anonymous_access_key' => 'a3D3lHtDiy4DuaiI',
                       'anonymous_access_secret' => '2VyGmZIrWs1ymCv4');
*/
      
      
//      $url_to_sign = $endpoint_strs['oauth-request-token-endpoint'];
     
      
      
   
   ?>
   
   
   
   
</head>

<body>
   <h2>TP Auth Example</h2>

</body>
</html>