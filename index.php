<html>

<head>

   <title>Claire - Demos</title>
   <link rel="stylesheet" href="tp-libraries/styles.css" type="text/css" />

</head>

<body>

<h2>Claire Demos</h2>

<p>These are simple demos of Braided Comments that connect with custom FB apps.</p>

<h3><a href="tp_blog_demo/">TypePad Blog Demo: Hobbitted</a></h3>
<p>The php pages in this demo display the content of an existing TypePad Blog (<a href="http://nataliepo.typepad.com/hobbitted">viewable here</a>) using the publicly available <a href="http://developer.typepad.com/">TypePad API's</a>.  The index shows the recent posts in that blog, and clicking through to each post will show an unstyled entry archive with the posts' Favorites and Comments.  </p>

<!--<p>In addition to TypePad Comments, some posts will display Facebook comments collected from <a href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_366639765016&ref=ts">the Hobbitted Fan Page's Braided tab</a>.  Look in the right sidebar's Comment listing <a href="tp_blog_demo/entry.php?xid=6a00e5539faa3b883301310f284ed8970c">here</a> for an example, where the username 'nataliepo' is a TypePad comment, and username 'Natalie Podrazik' is a FB comment.</p>-->

<br/>

<h3><a href="mt_blog_demo/">Movable Type Blog Demo: "Recent News"</a></h3>
<p>This demo shows the braiding of TPConnect comments with FB Comments (via <a href="http://github.com/nataliepo/Rousseau">Rousseau</a>), with the source as a published entry in Movable Type.</p>

<p>Here are some relevant pages:
<ol>
   <li><a href="http://mtcs-demo.apperceptive.com/testmt/recent_news/">Blog Index</a></li>
   <li><a href="http://mtcs-demo.apperceptive.com/testmt/recent_news/tab.php">tab.php</a> serving the FB Fan page.  View source to see the <code><fb:comment></code> blocks and the xid parameters. </li>
   <li><a href="http://www.facebook.com/pages/Hobbitted/358069609094?v=app_368383693541&ref=ts">Demo FB App</a> as a tab on the Hobbitted Fan page</li>
   <li><a href="http://mtcs-demo.apperceptive.com/testmt/recent_news/2010/03/performance-artist-mimics-performance-artist-at-moma.php">Rousseau in action</a> alongside a published entry</li>
   <li>A few <a href="mt_blog_demo/">local pages</a> that pull comments from Rousseau</li>
</ol>
<p>Comment posting is happening through a TPConnect section at the bottom with the presentation of TypePad comments removed via JavaScript.  This is only until Claire supports comment posting without needing TPConnect's submission form.</p>
<br />

<!--
<h3><a href="just_facebook">Facebook standalone page</a></h3>
<p>This is a one-pager showing how to serve Facebook comments.</p>
<br />

<h3><a href="just_tp_and_facebook">TypePad and Facebook standalone page</a></h3>
<p>This is a one-pager showing how to serve TypePad and FB comments without using the supporting Claire libraries.</p>
-->

<h3><a href="oauth/">OAuth Example</a></h3>
<p>When you <a href="oauth/index.php">log in</a> to my simple OAuth demo, your smiling TypePad avatar will show up on the index.  Try it, and send errors to <a href="mailto:npodrazik@sixapart.com">natalie</a> in case they come up.</p>


</body>

</html>
      
   