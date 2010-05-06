<html>

<head>
   <title>Claire</title>
   <?php 
      define ("DEFAULT_DEBUG_MODE", 1);
      include_once('../tp-libraries/tp-utilities.php'); 
   ?>
   
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
</head>
<body>
   
   <h2>Author Demo</h2>
   
   <h5>Here are the authors I have listed:</h5>
   <ol>
   <?php
   
      $authors = array('nataliepo', 'mmmeow', 'nywbc', 'wtfbklyn', 'capndesign');
      
      foreach ($authors as $author_str) {
         $a = new Author(array('username' => $author_str));
         echo "<li><img class='avatar' src='" . $a->avatar . "' /> " . 
            "<a href='" . $a->profile_url . "'>". 
            $a->display_name . "</a></li>";
      }   
   ?>
   </ol>
   
</body>
</html>
