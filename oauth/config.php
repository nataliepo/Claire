<?php

   define ("DEFAULT_DEBUG_MODE", 0);


   define ("BLOG_XID", "0");    
   define ("FACEBOOK_POST_ID_PREFIX", "");
   
   define ('TEMP_COOKIE', 'claire-temp-cookie');
   define ('COOKIE_NAME', 'claire-session');


// THESE ARE THE LOCALHOST SETTINGS
/*
   define ('CONSUMER_KEY', 'c5139cef2985b86d');
   define ('CONSUMER_SECRET', 'K0J0Im71');
   define ('CALLBACK_URL', 'http://127.0.0.1/claire/oauth/index.php');
   
   define ('DB_HOST', 'localhost');
   define ('DB_USERNAME', 'rocky');
   define ('DB_PASSWORD', 'four');
   define ('DB_NAME', 'oauth_test');
*/

// THESE ARE THE DEV3 SETTINGS

   define ('CONSUMER_KEY', '0ad999b15fb10bef');
   define ('CONSUMER_SECRET', 'rmipdBZd');
   define ('CALLBACK_URL', 'http://dev3.apperceptive.com/claire/oauth/index.php');

   define ('DB_HOST', 'localhost');
   define ('DB_USERNAME', 'rocky');
   define ('DB_PASSWORD', 'four');
   define ('DB_NAME', 'oauth_test');


   include_once('../tp-libraries/tp-utilities.php');
   
?>