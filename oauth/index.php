<?php
   define ('CONSUMER_KEY', 'c5139cef2985b86d');
 
   include_once ("oauth-php-98/library/OAuthStore.php");
   include_once ("oauth-php-98/library/OAuthRequester.php");

   include_once('config.php'); 
   
   $author = "";

   function print_as_table($array) {
      print "<table border='1'><thead><tr><th>Key</th><th>Value</th></tr></thead><tbody>";
      foreach(array_keys($array) as $key) {
         print "<tr><td>$key</td><td>$array[$key]</td></tr>";
      }
      print "</tbody></table>";
   }
   
   $db_options = array('server' => 'localhost',
                       'username' => 'rocky',
                       'password' => 'four',
                       'database' => 'oauth_test');

   $user_id = 1;         

   $store   = OAuthStore::instance('MySQL', $db_options);      
   $servers = $store->listServers('', $user_id);
   
   $response = array();
   foreach (array_keys($_GET) as $key) {
      $response[$key] = $_GET[$key];
   }
     
     // this gets the API urls again; they should probably be stored in the sql
     // table, but i'm not sure if they are right now -- requesting anyway!
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
      
   if (array_key_exists('oauth_token', $_GET)) {
      // fetch the Request Token Secret

      try {
         $r = $store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 
                                          'request', $user_id);
      }
      catch (OAuthException2 $e) {
         var_dump($e);
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


      $handle = fopen($final_url, "rb");
      $doc = stream_get_contents($handle);

      if (!$doc) {
         echo "<h1>Your session has expired.  <a href='alpha.php'>Log In</a> again!</h1>";
      }
      else {    
         $response = array();    
         $response_array = explode("&", $doc);
         foreach ($response_array as $response_str) {
            $pair = explode("=", $response_str);
            $response[$pair[0]] = $pair[1];
         }   


         //   $typepad_url = 'https://api.typepad.com/users/@self.js';
         $typepad_url = 'https://api.typepad.com/users/6p00e5539faa3b8833.json';
   
         $header_string = 'OAuth realm="api.typepad.com", ';
   
         $parameters = array('timestamp', 'nonce', 'consumer_key', 
                  'version', 'signature_method', 'signature');
         foreach ($parameters as $parm) {
            $header_string .= 'oauth_' . $parm . 
                     '="' . $oauth->getParam('oauth_' . $parm) . '", ';
         }
    
         $header_string .= 'oauth_token="' . $response['oauth_token'] . '"';

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

   <a class="next" href="index.php">Start Session Over</a>

<?php
   
   if (!$author) {
      echo "<h1>Your session has expired.  <a href='alpha.php'>Log In</a> again!</h1>";
   }
   else {
      echo "<h3 align='center'>Welcome back, <a href='" . $author->profile_url . "'>" . 
            $author->display_name . "</a></h3>";
   }
?>


</body>
</html>
