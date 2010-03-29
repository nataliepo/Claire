<?php
   define ('CONSUMER_KEY', 'c5139cef2985b86d');
 
   include_once ("oauth-php-98/library/OAuthStore.php");
   include_once ("oauth-php-98/library/OAuthRequester.php");

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

   $store   = OAuthStore::instance('MySQL', $db_options);      
   $servers = $store->listServers('', $user_id);
   $user_id = 1;         
   
   $response = array();
   foreach (array_keys($_GET) as $key) {
      $response[$key] = $_GET[$key];
   }
     
/*
   echo '<h3>_GET parameters...</h3>';
   print_as_table($_GET);
     
   echo '<h3>_POST parameters...</h3>';
   print_as_table($_POST);
     
   echo '<h3>_COOKIES...</h3>';
   print_as_table($_COOKIE);
*/
     
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


  
    /*
       Three pieces are needed for this step:
          1. oauth_token --> as a _GET parameter
          2. oauth_token_secret --> as a cookie
          3. verifier --> as a _GET parameter
     */
  /*  $oauth_token = $_GET['oauth_token'];
    $oauth_token_secret = $_COOKIE['claire-session'];
    $verifier = $_GET['oauth_verifier'];
  */

   // fetch the Request Token Secret
   $r = $store->getServerTokenSecrets(CONSUMER_KEY, $_GET['oauth_token'], 
                                     'request', $user_id);

   // make a generic Request object, and then sign it with the secret token
   $oauth 	= new OAuthRequester($uri, $method, $params);
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
    
   $response = array();    
   $response_array = split("&", $doc);
   foreach ($response_array as $response_str) {
      $pair = split("=", $response_str);
      $response[$pair[0]] = $pair[1];
   }
   var_dump($response);
   echo "<h3>server response is ^ </h3>";
   
   
   // now, make a request...if only I knew how :/
   
//   $final_url = 'https://api.typepad.com/users/@self.js' . '?';
/*   $final_url = 'WWW-Authenticate: OAuth realm="http://localhost/claire/oauth", ';
   
   
//   header('WWW-Authenticate: Basic realm="Secret page"');
   $header_array = array();
   $parameters = array('timestamp', 'nonce', 'consumer_key', 
             'version', 'signature_method', 'signature');
    foreach ($parameters as $parm) {
       echo "<p>parameter[oauth_" . $parm . "]=" . $oauth->getParam('oauth_' . $parm) . "</p>";
//       $final_url .= 'oauth_' . $parm . '=' . $oauth->getParam('oauth_' . $parm) . '&';
         $final_url .= 'oauth_' . $parm . '="' . $oauth->getParam('oauth_' . $parm) . '",';

       $header_array['oauth_' . $parm] = $oauth->getParam('oauth_' . $parm);
    }
    
   $header_array['oauth_token'] = $response['oauth_token'];
   
   $final_url .= 'oauth_token=' . $response['oauth_token'];
   
   var_dump($final_url);
*/
   
      /* this wont work since we're pulling https now */

/*   $handle = fopen($final_url, "rb");
   $doc = stream_get_contents($handle);
   var_dump($doc);
   echo '<h3>Response for @self is ^</h3>';
*/

/*
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $final_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array);
   $response = json_decode(curl_exec($ch));
   curl_close($ch);
   var_dump($response);
   echo '<h3>Response for @self is ^</h3>';
   
  */ 

   
   

?>


<html>
<head>
   <title>TP Auth Example</title>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

</head>

<body>
   <h2>Redirected from TypePad...</h2>
   <p>...now what? :)</p>

</body>
</html>
