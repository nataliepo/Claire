<?php
// FOR MT TESTING

    define ("FACEBOOK_POST_ID_PREFIX", "fb-animals-");
    define ("FACEBOOK_API_KEY", "336fb869587da7159dc0c56337183c3d");
    define ("FACEBOOK_API_SECRET", "d3db6494609e56aec9bf3392f69aff40");

    define ("DEFAULT_DEBUG_MODE", 1);

    define ("BLOG_XID", "6a00e5539faa3b88330120a94362b9970b");

// LOCALHOST SETTINGS

    define ('CONSUMER_KEY', '67738f8572da988f');
    define ('CONSUMER_SECRET', 'BHi2Kre5');
    define ('CALLBACK_URL', 'http://127.0.0.1/claire/mt_blog_demo/performance_artist.php');

    define ('DB_HOST', 'localhost');
    define ('DB_USERNAME', 'rocky');
    define ('DB_PASSWORD', 'four');
    define ('DB_NAME', 'posting_comments');

    define ('COOKIE_NAME', 'claire-comment-posting');


// DEV3 SETTINGS
/*
   define ('COOKIE_NAME', 'claire-session');

   define ('CONSUMER_KEY', '0ad999b15fb10bef');
   define ('CONSUMER_SECRET', 'rmipdBZd');
   define ('CALLBACK_URL', 'http://dev3.apperceptive.com/claire/mt_blog_demo/index.php');

   define ('DB_HOST', 'localhost');
   define ('DB_USERNAME', 'rocky');
   define ('DB_PASSWORD', 'four');
   define ('DB_NAME', 'oauth_test');
*/



    include_once('../tp-libraries/tp-utilities.php'); 
    
    include_once ("../oauth/oauth-php-98/library/OAuthStore.php");
    include_once ("../oauth/oauth-php-98/library/OAuthRequester.php");
?>