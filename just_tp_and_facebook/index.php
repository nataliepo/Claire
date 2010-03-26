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
            $content = "<h3>This is a local page serving Facebook AND TypePad comments.</h3>";
            $encoded_content = urlencode($content);
            $permalink = "http://localhost/just_tp_and_facebook/index.php";
            $fb_prefix = "nataliepo_";
            $xid = '6a00e5539faa3b88330120a970f9cb970b';
            $html = 1;
            
            $params = "content=$encoded_content&permalink=$permalink&xid=$xid&fb_prefix=$fb_prefix&HTML=$html";
            
            
            $ch = curl_init (ROUSSEAU_COMMENTS_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $comments = curl_exec($ch);
            
         ?>
         
         <title>Claire: Facebook and TypePad Comments example</title>
         

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