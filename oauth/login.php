<?php
   include_once ("oauth-php-98/library/OAuthStore.php");
   include_once ("oauth-php-98/library/OAuthRequester.php");
      
   include_once('config.php'); 
   
   // Create a new session object.  It will flush any local cookies that you have in case
   // you hit this page in error, since this should be the "Login" page.
   $user_session = new TPSession();

   // Obtain a Request token from TypePad.  
   $user_session->request_and_verify_request_token();
   
      
   // Next step in the OAuth Dance: Redirect your user to the Provider.
   // this redirect() method is courtesy of our OAuthPHP lib. Parameters:
   //   1 = the URL to redirect to
   //   2 = a list of parameters. In our case, it's the Request token.
   /*
    * 'oauth-authorization-page' is deprecated in favor of 'oauthAuthorizationUrl'.    
      OAuthRequest::redirect($user_session->get_api_endpoint('oauth-authorization-page'), 
                          array('oauth_token' => $user_session->oauth_token));
     */
     
     OAuthRequest::redirect($user_session->get_api_endpoint(TP_OAUTH_AUTH_URL), 
                         array('oauth_token' => $user_session->oauth_token));
     
?><?php
   // FYI: HTML is forbidden in this file, since the redirect's headers cannot be written!!
?>   