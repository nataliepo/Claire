<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   $content = '<b>Rabbits</b> are small <a href="http://en.wikipedia.org/wiki/Mammal" title="Mammal">mammals</a> in the <a href="http://en.wikipedia.org/wiki/Family_%28biology%29" title="Family (biology)">family</a> <a href="http://en.wikipedia.org/wiki/Leporidae" title="Leporidae">Leporidae</a> of the order <a href="http://en.wikipedia.org/wiki/Lagomorpha" title="Lagomorpha">Lagomorpha</a>, found in several parts of the world. There are seven different <a href="http://en.wikipedia.org/wiki/Genus" title="Genus">genera</a> in the family <a href="http://en.wikipedia.org/wiki/Taxonomy" title="Taxonomy">classified</a> as rabbits, including the <a href="http://en.wikipedia.org/wiki/European_rabbit" title="European rabbit" class="mw-redirect">European rabbit</a> (<i>Oryctolagus cuniculus</i>), <a href="http://en.wikipedia.org/wiki/Cottontail_rabbit" title="Cottontail rabbit">Cottontail rabbit</a> (genus <i>Sylvilagus</i>; 13 <a href="http://en.wikipedia.org/wiki/Species" title="Species">species</a>), and the <a href="http://en.wikipedia.org/wiki/Amami_rabbit" title="Amami rabbit" class="mw-redirect">Amami rabbit</a> (<i>Pentalagus furnessi</i>, <a href="http://en.wikipedia.org/wiki/Endangered_species" title="Endangered species">endangered species</a> on <a href="http://en.wikipedia.org/wiki/Amami_%C5%8Cshima" title="Amami Ōshima">Amami Ōshima</a>, <a href="http://en.wikipedia.org/wiki/Japan" title="Japan">Japan</a>). There are many other species of rabbit, and these, along with <a href="http://en.wikipedia.org/wiki/Pika" title="Pika">pikas</a> and <a href="http://en.wikipedia.org/wiki/Hare" title="Hare">hares</a>, make up the <a href="http://en.wikipedia.org/wiki/Order_%28biology%29" title="Order (biology)">order</a> <a href="http://en.wikipedia.org/wiki/Lagomorpha" title="Lagomorpha">Lagomorpha</a>. ';
    
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/rabbits.php',
                                  '62',
                                  $content);



//   $comments = $tp_entry->braided_comments();
   $comments = $tp_entry->rousseaus_listing();

?>

   
</head>

<body>
   
   <h2><a href="index.php">Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</a></h2>

   <a class="next" href="http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/rabbits.php" target="_blank">View Entry on Movable Type</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php
   echo $comments;
/*
         foreach ($comments as $comment) {
            echo '
      <div class="comment-outer">
         <div class="comment-avatar">
            <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
         </div>
         <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote <p>' . 
               $comment->content . '</p> on ' . $comment->time() . '<br />
         </div>
      </div>';                  
         }
*/
      ?>
   </div>
   
</body>
</html>