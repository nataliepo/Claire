# Rousseau
'Rousseau' is a proof-of-concept site that builds a set of comments from various sources.  It uses a set of utility php libraries that pull from both the TypePad Blog APIs and the Facebook APIs.

# Installation
* Place the index.php and tp-libraries code on the same level on your php-enabled webserver.

# Choose Active FB App
The tp-libraries/tp-config.php file has a section that says "FOR HOBBITTED" and another section that say "FOR MT TESTING".  Read on to figure out how to use each...

## To see FB Comments + TypePad Blog Comments (Hobbitted Demo)
* Replace:
      // FOR HOBBITTED
      /*
         define ("FACEBOOK_POST_ID_PREFIX", "braided_comments-");
         ...
      */
      // FOR MT TESTING
      
         define ("FACEBOOK_POST_ID_PREFIX", "fb-animals-");
         ...
      
with
      // FOR HOBBITTED
      
         define ("FACEBOOK_POST_ID_PREFIX", "braided_comments-");
         ...
      
      // FOR MT TESTING
      /*
         define ("FACEBOOK_POST_ID_PREFIX", "fb-animals-");
         ...
      */
which essentially comments out the MT Testing FB app connectivity info.

Then, view the index of that folder in your browser.  You'll see a simple comment listing from my Hobbitted "Plotzed" blog post.  Pick an entry.  I suggest the "Some ill sh*t is overdue..." post, since it has FB and TP comments.  Look in the Comments listing for avatars with the little Facebook icon in the corner -- that comment listing is Braided.


## To see the FB Comments + TPConnect Comments via MT-Published Blog (MT-Animal demo)
* Make sure the FOR HOBBITTED section is commented out and the FOR MT TESTING section is not.

Then, view the mt_entry_test.php entry in your browser.  You should see at least 2 TPConnect comments and 1 FB comment.  The rest of the entry content lives on http://mtcs-demo.apperceptive.com/testmt/animals/2010/03/sloth.html, which was published by MT.  





