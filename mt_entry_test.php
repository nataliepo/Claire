<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>ajax_test</title>
   <link rel="stylesheet" href="styles.css" type="text/css" />
   
<?php
   include_once('tp-libraries/tp-utilities.php'); 
   
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html',
                                  '61');

   $comments = $tp_entry->braided_comments();

?>

   
</head>

<body>
   
   <h1>Comment Ajax Text</h1>


   <div class="comments">
        <h5>Comments</h5>

           <?php

              foreach ($comments as $comment) {
                              echo 
              '<div class="comment-outer">
                  <div class="comment-avatar">
                      <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
                  </div>
                  <div class="comment-contents">
                      <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a>
                      wrote <p>' . 
                          $comment->content . 
                      '</p> on ' . $comment->timestamp . '<br />
                  </div>
              </div>';                  
              }
           ?>
     </div>
   </div>


<?php     

  




 /*  $post_url = 'http://api.typepad.com/blogs/6a00e5539faa3b88330120a94362b9970b/discover-external-post-asset.json';
   $params =  array("permalinkUrl" => "http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html");
   $json = '{"permalinkUrl":"http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html"}';
   
//   $comments = grab_comments($json);
   $comments = post_json($post_url, $json);
   
   // now parse the result
   $links = $comments->asset->links;
   
   $comment_url = "";
   foreach ($links as $link ){
      if ($link->rel == "replies") {
         $comment_url = $link->href;
      }
   }
   
   if ($comment_url) {
      
   }
   
   echo "<p>Comment_URL = $comment_url</p>";
   
   
   
   
         
   function grab_comments($json) {
      $ch = curl_init(POSTURL);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         "Content-Type: application/json;"));
         
      $result = curl_exec($ch);
      
      
      return parse_result($result);
      
   }
   
   function parse_result ($entry_str) {
      
      // THIS IS A STRING, NOT A JSON OBJECT.  FIGURE OUT HOW TO WORK WITH IT!
      
      
      $json = json_decode($entry_str);
      echo var_dump($json);
      echo "<p>Entry's authorname = " . $json->asset->author->displayName . "</p>";
      echo "<p>[parse_result] json->asset = " . $json['asset'] . "</p>";
   }

*/      
?>

   
   
   

   </body>

   </html>