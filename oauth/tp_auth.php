<html>
<head>
   <title>TP Auth Example</title>
   
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />
   
   <?php
   
      include_once ("oauth-php-98/library/OAuthStore.php");
      include_once ("oauth-php-98/library/OAuthRequester.php");
      
   
      $options = array('consumer_key' => 'c5139cef2985b86d', 
                       'consumer_secret' => 'K0J0Im71',
                       'anonymous_access_key' => 'a3D3lHtDiy4DuaiI',
                       'anonymous_access_secret' => '2VyGmZIrWs1ymCv4');

      
      // First, make the request to TypePad to get the necessary OAuth URlS.
      $url = 'http://api.typepad.com/api-keys/' . $options['consumer_key'] . '.json';      
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
   
       foreach (array_keys($endpoint_strs) as $str) {
          echo "<p>" . $str . " = " . $endpoint_strs[$str] . "</p>";
      }
      
//      $url_to_sign = $endpoint_strs['oauth-request-token-endpoint'];
      

      
      echo "<p>making a request to " . $endpoint_strs['oauth-request-token-endpoint'] . "</p>";
      $method = 'GET';
      $params = null;
      try {
         $request = new OAuthRequester($endpoint_strs['oauth-request-token-endpoint'], 
               $method, $params);
         
         $result = $request->doRequest($options['consumer_key']);
         $response = $result['body'];
      }
      catch (OAuthException2 $e) {
         var_dump ($e);
         echo "<p>OAUTH EXCEPTION ^</p>";
      }
   
   ?>
   
   
   
   
</head>

<body>
   <h2>TP Auth Example</h2>

</body>
</html>