<?php
// FOR MT TESTING

    define ("FACEBOOK_POST_ID_PREFIX", "fb-animals-");
    define ("FACEBOOK_API_KEY", "336fb869587da7159dc0c56337183c3d");
    define ("FACEBOOK_API_SECRET", "d3db6494609e56aec9bf3392f69aff40");

    define ("DEFAULT_DEBUG_MODE", 1);

    define ("BLOG_XID", "6a00e5539faa3b88330120a94362b9970b");
    
    define ('CONSUMER_KEY', '67738f8572da988f');
    define ('CONSUMER_SECRET', 'BHi2Kre5');
    define ('CALLBACK_URL', 'http://127.0.0.1/claire/mt_blog_demo/performance_artist.php');

    define ('DB_HOST', 'localhost');
    define ('DB_USERNAME', 'rocky');
    define ('DB_PASSWORD', 'four');
    define ('DB_NAME', 'posting_comments');

    define ('COOKIE_NAME', 'claire-comment-posting');


    include_once('../tp-libraries/tp-utilities.php'); 
    
    include_once ("../oauth/oauth-php-98/library/OAuthStore.php");
    include_once ("../oauth/oauth-php-98/library/OAuthRequester.php");
?>