<?php

   define ("DEFAULT_DEBUG_MODE", 1);

   define ("BLOG_XID", "6a0120a7ee9b66970b0120a7ee9b6d970b");    
   define ("FACEBOOK_POST_ID_PREFIX", "freebie-");
   
   
// LOCAL HOST SETTINGS...

   define ('COOKIE_NAME', 'claire-comment-posting');

   define ('CONSUMER_KEY', '67738f8572da988f');
   define ('CONSUMER_SECRET', 'BHi2Kre5');
   define ('CALLBACK_URL', 'http://127.0.0.1/claire/posting_comments/index.php');
   
   define ('DB_HOST', 'localhost');
   define ('DB_USERNAME', 'rocky');
   define ('DB_PASSWORD', 'four');
//   define ('DB_NAME', 'posting_comments');
   define ('DB_NAME', 'oauth_test');
   


// DEV3 SETTINGS...
/*
   define ('COOKIE_NAME', 'claire-session');
   
   define ('CONSUMER_KEY', '0ad999b15fb10bef');
   define ('CONSUMER_SECRET', 'rmipdBZd');
   define ('CALLBACK_URL', 'http://dev3.apperceptive.com/claire/posting_comments/index.php');

   define ('DB_HOST', 'localhost');
   define ('DB_USERNAME', 'rocky');
   define ('DB_PASSWORD', 'four');
   define ('DB_NAME', 'oauth_test');
*/

   include_once ('../tp-libraries/tp-utilities.php');
   include_once ("../oauth/oauth-php-98/library/OAuthStore.php");
   include_once ("../oauth/oauth-php-98/library/OAuthRequester.php");   
?>