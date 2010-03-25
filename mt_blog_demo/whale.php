<html>
<head>
   <meta http-equiv="Content-type" content="text/html; charset=utf-8">
   <title>Movable Type - TPConnect + FBComments Demo</title>
   <link rel="stylesheet" href="../tp-libraries/styles.css" type="text/css" />
   
<?php
   include_once('config.php');
   $content = '                                        <p><b>Whale</b> (origin <a href="http://en.wikipedia.org/wiki/Old_English" title="Old English">Old English</a> <i>hwael</i>)<sup id="cite_ref-OED_1-0" class="reference"><a href="http://en.wikipedia.org/wiki/Whale#cite_note-OED-1"><span>[</span>2<span>]</span></a></sup> is the common name for various <a href="http://en.wikipedia.org/wiki/Marine_mammal" title="Marine mammal">marine mammals</a> of the order <a href="http://en.wikipedia.org/wiki/Cetacea" title="Cetacea">Cetacea</a>.<sup id="cite_ref-OED_1-1" class="reference"><a href="http://en.wikipedia.org/wiki/Whale#cite_note-OED-1"><span>[</span>2<span>]</span></a></sup> The term <i>whale</i> is sometimes used to refer to all cetaceans, but more often it excludes <a href="http://en.wikipedia.org/wiki/Dolphins" title="Dolphins" class="mw-redirect">dolphins</a> and <a href="http://en.wikipedia.org/wiki/Porpoises" title="Porpoises" class="mw-redirect">porpoises</a>, which are also <a href="http://en.wikipedia.org/wiki/Cetacea" title="Cetacea">cetaceans</a><sup id="cite_ref-2" class="reference"><a href="http://en.wikipedia.org/wiki/Whale#cite_note-2"><span>[</span>3<span>]</span></a></sup> but belong to the suborder <i>Odontoceti</i> (<a href="http://en.wikipedia.org/wiki/Toothed_whale" title="Toothed whale">toothed whales</a>). This suborder also includes the <a href="http://en.wikipedia.org/wiki/Sperm_whale" title="Sperm whale">sperm whale</a>, <a href="http://en.wikipedia.org/wiki/Killer_whale" title="Killer whale">killer whale</a>, <a href="http://en.wikipedia.org/wiki/Pilot_whale" title="Pilot whale">pilot whale</a>, and <a href="http://en.wikipedia.org/wiki/Beluga_whale" title="Beluga whale">beluga whale</a>. The suborder <i>Mysticeti</i> (<a href="http://en.wikipedia.org/wiki/Baleen_whale" title="Baleen whale">baleen whales</a>), are <a href="http://en.wikipedia.org/wiki/Filter_feeder" title="Filter feeder">filter feeders</a> that feed on small organisms caught by straining seawater through a comblike structure found in the mouth called <a href="http://en.wikipedia.org/wiki/Baleen" title="Baleen">baleen</a>. This suborder includes the <a href="http://en.wikipedia.org/wiki/Blue_whale" title="Blue whale">blue whale</a>, the <a href="http://en.wikipedia.org/wiki/Humpback_whale" title="Humpback whale">humpback whale</a> the <a href="http://en.wikipedia.org/wiki/Bowhead_whale" title="Bowhead whale">bowhead whale</a> and the <a href="http://en.wikipedia.org/wiki/Minke_whale" title="Minke whale">minke whales</a>. All Cetacea have forelimbs modified as fins, a tail with horizontal flukes, and nasal openings on top of the head.</p>

   <p>Whales range in size from the blue whale, the <a href="http://en.wikipedia.org/wiki/Largest_organism" title="Largest organism" class="mw-redirect">largest animal</a> known to have ever existed<sup id="cite_ref-3" class="reference"><a href="http://en.wikipedia.org/wiki/Whale#cite_note-3"><span>[</span>4<span>]</span></a></sup> at 35&nbsp;m (115&nbsp;ft) and 150&nbsp;tonnes (150&nbsp;LT; 170&nbsp;ST), to various pygmy species, such as the <a href="http://en.wikipedia.org/wiki/Pygmy_sperm_whale" title="Pygmy sperm whale">pygmy sperm whale</a> at 3.5&nbsp;m (11&nbsp;ft).</p>

   <p>Whales collectively inhabit all the world\'s oceans and number in the
   millions, with population growth rate estimates for various assessed
   species ranging from 3% to 13%.<sup id="cite_ref-4" class="reference"><a href="http://en.wikipedia.org/wiki/Whale#cite_note-4"><span>[</span>5<span>]</span></a></sup> For centuries, whales have been <a href="http://en.wikipedia.org/wiki/Whaling" title="Whaling">hunted</a>
   for meat and as a source of raw materials. By the middle of the 20th
   century, however, industrial whaling had left many species seriously <a href="http://en.wikipedia.org/wiki/Endangered_species" title="Endangered species">endangered</a>, leading to the end of whaling in all but a few countries.</p> ';
    
   $tp_entry = new TPConnectEntry('6a00e5539faa3b88330120a94362b9970b', 
                                  'http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/whales.php',
                                  '63',
                                  $content);



//   $comments = $tp_entry->braided_comments();
   $comments = $tp_entry->rousseaus_listing();

?>

   
</head>

<body>
   
   <h2><a href="index.php">Movable Type Blog Post (TPConnect Comments + FaceBook Fan Page Comments)</a></h2>

   <a class="next" href="http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/whales.php" target="_blank">View Entry on Movable Type</a> <br />
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