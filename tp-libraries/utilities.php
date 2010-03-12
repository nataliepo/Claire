<?php

/*****
 * utility features mostly borrowed from Tydget's typepad_parsing.js
 *****/
function get_resized_avatar ($user, $size) {
    // use the lilypad as a default in case all else fails
    $default_avatar = 'http://up3.typepad.com/6a00d83451c82369e20120a4e574c1970b-50si';
    
    $links_array = $user->links;
    foreach ($links_array as $link) {
        if ($link->rel == "avatar") {
            if ($link->width < 125) {
                return $link->href;
            } 
        }
    }

   return $default_avatar;
}


?>