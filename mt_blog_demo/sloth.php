<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   $content = '<img alt="sloth.jpg" src="http://mtcs-demo.apperceptive.com/testmt/animals/sloth.jpg" class="mt-image-left" style="margin: 0pt 20px 20px 0pt; float: left;" height="293" width="220" />Sloths comprise six <a href="http://en.wikipedia.org/wiki/Species" title="Species">species</a> of medium-sized <a href="http://en.wikipedia.org/wiki/Mammal" title="Mammal">mammals</a> belonging to the <a href="http://en.wikipedia.org/wiki/Family_%28biology%29" title="Family (biology)">families</a> <a href="http://en.wikipedia.org/wiki/Two-toed_sloth" title="Two-toed sloth">Megalonychidae</a> and <a href="http://en.wikipedia.org/wiki/Three-toed_sloth" title="Three-toed sloth">Bradypodidae</a>, part of the <a href="http://en.wikipedia.org/wiki/Order_%28biology%29" title="Order (biology)">order</a> <a href="http://en.wikipedia.org/wiki/Pilosa" title="Pilosa">Pilosa</a>. They are <a href="http://en.wikipedia.org/wiki/Arboreal_locomotion" title="Arboreal locomotion">arboreal</a> residents of the <a href="http://en.wikipedia.org/wiki/Tropical_rainforest" title="Tropical rainforest">rainforests</a> of <a href="http://en.wikipedia.org/wiki/Central_America" title="Central America">Central</a> and <a href="http://en.wikipedia.org/wiki/South_America" title="South America">South America</a>. The sloth\'s taxonomic <a href="http://en.wikipedia.org/wiki/Taxonomic_rank" title="Taxonomic rank">suborder</a> is <b>Folivora</b>, while some call it <b>Phyllophaga</b>. Both names mean "leaf-eaters"; the first is derived from <a href="http://en.wikipedia.org/wiki/Latin" title="Latin">Latin</a>, the second from <a href="http://en.wikipedia.org/wiki/Greek_language" title="Greek language">Greek</a>. Names for the animals used by tribes in <a href="http://en.wikipedia.org/wiki/Ecuador" title="Ecuador">Ecuador</a> include <b>Ritto</b>, <b>Rit</b> and <b>Ridette</b>, mostly forms of the word "sleep", "eat" and "dirty" from <a href="http://en.wikipedia.org/wiki/Tagaeri" title="Tagaeri">Tagaeri</a> tribe of <a href="http://en.wikipedia.org/wiki/Huaorani" title="Huaorani">Huaorani</a>,
   in Brazil sloths are commonly called "Bicho-preguiça" ("lazy animal")
   because of slow movements related to their very low metabolism.<br /><br /><p>Sloths are considered to be <a href="http://en.wikipedia.org/wiki/Folivore" title="Folivore">folivores</a> as the bulk of their diet consists mostly of buds, tender shoots, and leaves, mainly of <i><a href="http://en.wikipedia.org/wiki/Cecropia" title="Cecropia">Cecropia</a></i> trees. Some two-toed sloths have been documented as eating <a href="http://en.wikipedia.org/wiki/Insect" title="Insect">insects</a>, small reptiles and birds as a small supplement to their diet. They have made extraordinary adaptations to an <a href="http://en.wikipedia.org/wiki/Arboreal" title="Arboreal" class="mw-redirect">arboreal</a>

   browsing lifestyle. Leaves, their main food source, provide very little
   energy or nutrition and do not digest easily. Sloths therefore have
   very large, specialized, slow-acting <a href="http://en.wikipedia.org/wiki/Stomach" title="Stomach">stomachs</a> with multiple compartments in which <a href="http://en.wikipedia.org/wiki/Symbiosis" title="Symbiosis">symbiotic</a> <a href="http://en.wikipedia.org/wiki/Bacteria" title="Bacteria">bacteria</a>
   break down the tough leaves. As much as two-thirds of a well-fed
   sloth\'s body-weight consists of the contents of its stomach, and the
   digestive process can take a month or more to complete.</p>
   <p>Even so, leaves provide little energy, and sloths deal with this by a range of economy measures: they have very low <a href="http://en.wikipedia.org/wiki/Metabolism" title="Metabolism">metabolic</a>
   rates (less than half of that expected for a mammal of their size), and
   maintain low body temperatures when active (30&nbsp;°C (86&nbsp;°F) to 34&nbsp;°C
   (93&nbsp;°F)), and still lower temperatures when resting.</p>

   <p>Although unable to survive outside the tropical rainforests of South
   and Central America, within that environment sloths are outstandingly
   successful creatures: they can account for as much as half the total
   energy consumption and two-thirds of the total terrestrial mammalian <a href="http://en.wikipedia.org/wiki/Biomass" title="Biomass">biomass</a> in some areas.<sup class="Template-Fact" title="This claim needs references to reliable sources from February 2007" style="white-space: nowrap;">[<i><a href="http://en.wikipedia.org/wiki/Wikipedia:Citation_needed" title="Wikipedia:Citation needed">citation needed</a></i>]</sup> Of the six living <a href="http://en.wikipedia.org/wiki/Species" title="Species">species</a>, only one, the <a href="http://en.wikipedia.org/wiki/Maned_Three-toed_Sloth" title="Maned Three-toed Sloth" class="mw-redirect">Maned Three-toed Sloth</a> (<i>Bradypus torquatus</i>),
   has a classification of "endangered" at present. The ongoing
   destruction of South America\'s forests, however, may soon prove a
   threat to other sloth species.</p><br />';  
    
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.php',
                                  '61',
                                  '2010-03-23 13:00:25',
                                  $content);
/*
$tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sea-otter.php',
                                '60',
                                $content);
*/


//   $comments = $tp_entry->braided_comments();
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