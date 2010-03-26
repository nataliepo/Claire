<html>
    <head>
       
       <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
       
       
        <?php 
           function debug ($msg) {
              if (DEFAULT_DEBUG_MODE) {
                 echo '<p class="debug">' . $msg . '</p>';
              }
           }
           
           
            define ("ROUSSEAU_COMMENTS_URL", "http://dev3.apperceptive.com/rousseau/comments.php");
            
            // i'll submit these vars to rousseau.  
            $content = "<h3>This is just a local page serving Facebook comments.</h3>";
            $encoded_content = urlencode($content);
            $permalink = "http://localhost/claire/just_facebook/index.php";
            $fb_id = "claire_1";
            $html = 1;
            
            $params = "content=$encoded_content&permalink=$permalink&fb_id=$fb_id&HTML=$html";
            
            
            $ch = curl_init (ROUSSEAU_COMMENTS_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $comments = curl_exec($ch);
            
         ?>
         
         <title>Claire: Just Facebook example</title>
         

     <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />

    </head>
        
    <body>
        <h2><a href="index.php">Claire Demo</a></h2>
        
        <?php
         //   echo '<a class="next" href="' . $entry->permalink . '" target="_blank">View Entry on TypePad</a>';
         //   echo '<h3><a href="' . $entry->author->profile_url . '"><img class="avatar" src="'. $entry->author->avatar . '" /></a>';
         //   echo  $entry->title . "</h3>";
         //   echo '<p>Posted at ' . $entry->time() . '</p>';
        ?>
              
      <div id="alpha">
         <div class="entry-body">
            <p> <?php echo $content; ?></p>
         </div>
      </div>
      
      <div id="beta"> 

        
        <div class="comments">
           <h5>Comments</h5>

              <?php
                 echo $comments;
                 
              ?>
        </div>
      </div> 
    </body>
</html>