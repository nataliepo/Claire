<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   
   $content  = '<p><img alt="250px-Sea_otter_cropped.jpg" src="http://mtcs-demo.apperceptive.com/testmt/animals/250px-Sea_otter_cropped.jpg" class="mt-image-left" style="margin: 0pt 20px 20px 0pt; float: left;" height="194" width="250" />The <b>sea otter</b> (<i>Enhydra lutris</i>) is a <a href="http://en.wikipedia.org/wiki/Marine_mammal" title="Marine mammal">marine mammal</a> native to the coasts of the northern and eastern <a href="http://en.wikipedia.org/wiki/Pacific_Ocean" title="Pacific Ocean">North Pacific Ocean</a>. Adult sea otters typically weigh between 14 and 45&nbsp;<a href="http://en.wikipedia.org/wiki/Kilogram" title="Kilogram">kg</a> (30 to 100&nbsp;<a href="http://en.wikipedia.org/wiki/Pound_%28mass%29" title="Pound (mass)">lb</a>), making them the heaviest members of the <a href="http://en.wikipedia.org/wiki/Mustelidae" title="Mustelidae">weasel family</a>,
   but among the smallest marine mammals. Unlike most marine mammals, the
   sea otter\'s primary form of insulation is an exceptionally thick coat
   of <a href="http://en.wikipedia.org/wiki/Fur" title="Fur">fur</a>, the densest in the animal kingdom. Although it can walk on land, the sea otter lives mostly in the ocean.</p>

   <p>The sea otter inhabits nearshore environments where it dives to the sea floor to <a href="http://en.wikipedia.org/wiki/Forage" title="Forage">forage</a>. It preys mostly upon marine invertebrates such as <a href="http://en.wikipedia.org/wiki/Sea_urchin" title="Sea urchin">sea urchins</a>, various <a href="http://en.wikipedia.org/wiki/Mollusc" title="Mollusc" class="mw-redirect">molluscs</a> and <a href="http://en.wikipedia.org/wiki/Crustacean" title="Crustacean">crustaceans</a>, and some species of <a href="http://en.wikipedia.org/wiki/Fish" title="Fish">fish</a>.
   Its foraging and eating habits are noteworthy in several respects.
   First, its use of rocks to dislodge prey and to open shells makes it
   one of the few mammal species to use tools. In most of its range, it is
   a <a href="http://en.wikipedia.org/wiki/Keystone_species" title="Keystone species">keystone species</a>, controlling sea urchin populations which would otherwise inflict extensive damage to <a href="http://en.wikipedia.org/wiki/Kelp_forest" title="Kelp forest">kelp forest</a> <a href="http://en.wikipedia.org/wiki/Ecosystem" title="Ecosystem">ecosystems</a>. Its diet includes prey species that are also valued by humans as food, leading to conflicts between sea otters and fisheries.</p>

   <p>Sea otters, whose numbers were once estimated at 150,000-300,000,
   were hunted extensively for their fur between 1741 and 1911, and the
   world population fell to 1,000-2,000 individuals in a fraction of their
   historic range. A subsequent international ban on hunting, conservation
   efforts, and reintroduction programs into previously populated areas
   have contributed to numbers rebounding, and the species now occupies
   about two-thirds of its former range. The recovery of the sea otter is
   considered an important success in <a href="http://en.wikipedia.org/wiki/Marine_conservation" title="Marine conservation">marine conservation</a>, although populations in the <a href="http://en.wikipedia.org/wiki/Aleutian_Islands" title="Aleutian Islands">Aleutian Islands</a> and <a href="http://en.wikipedia.org/wiki/California" title="California">California</a> have recently declined or have plateaued at depressed levels. For these reasons (as well as its particular vulnerability to <a href="http://en.wikipedia.org/wiki/Oil_spill" title="Oil spill">oil spills</a>) the sea otter remains classified as an <a href="http://en.wikipedia.org/wiki/Endangered_species" title="Endangered species">endangered species</a>.</p> ';

 //  $content = "<h3>HEYO!</h3>";
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sea-otter.php',
                                  '60',
                                  $content);

   $comments = $tp_entry->rousseaus_listing();

?>
   
</head>

<body>
   
   <h2>Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</h2>

   <a class="next" href="http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html" target="_blank">View Entry on Movable Type</a> <br />
   <a class="next" href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts" target="_blank">View FaceBook Fan Page Tab</a>

   <div class="comments">
      <h5>Comments</h5>

      <?php

         echo $comments;
       /*  foreach ($comments as $comment) {
            echo '
      <div class="comment-outer">
         <div class="comment-avatar">
            <a href="' . $comment->author->profile_url . '"><img class="avatar" src="' . $comment->author->avatar. '" /></a>
         </div>
         <div class="comment-contents">
            <a href="' . $comment->author->profile_url . '">' . $comment->author->display_name . '</a> wrote <p>' . $comment->content . '</p> on ' . 
                  $comment->time() . '<br />
         </div>
      </div>';                  
         }
         */
      ?>
   </div>
   
</body>
</html>