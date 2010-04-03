# USING Claire's OAUTH
Before you can use the libraries included in this package, you need to:

* Create a database and note its connectivity parameters.  They're hard-coded in index.php and alpha.php right now.
* Insert the tables defined here:
http://github.com/nataliepo/Claire/blob/master/oauth/oauth-php-98/library/store/mysql/mysql.sql . You should end up with 5 oauth tables.
* Correct one of the tables whose column is too skinny:
   alter table oauth_consumer_token change oct_token oct_token varchar(170);
* Hit http://localhost/claire/oauth in your browser.  You should see that you're logged out.  Follow the steps, and you should be sent back to index.php with an active session



# CHALLENGES

* The lib's default methods for formulating token requests were not what TypePad expected, so in the interest of Getting It To Work, I formed most of the requests myself and used the lib's signing methods

* This lib also provided the schema for remembering known oauth servers and individual users who authenticate, which was initially useful but buggy for multiple users on the site.  
      // in oauth-php-98/library/store/OAuthStoreSQL.php, addServerToken() method:
      // THIS IS A BUG
      $ocr_id = $this->query_one('
         SELECT ocr_id
         FROM oauth_consumer_registry
         WHERE ocr_consumer_key = \'%s\'
         ', $consumer_key);
changed it to:
      $ocr_id = $this->query_one('
         SELECT ocr_id
         FROM oauth_consumer_registry
         WHERE ocr_consumer_key = \'%s\'
         and
         ocr_usa_id_ref=%d
         ', $consumer_key,
         $user_id);
and had more success.

* The default schema provided did not yield columns wide enough for our consumer access token (their size defaults to 64; I've gotten some that are 132).  I had to alter it in order to respect TP's size:
      mysql> alter table oauth_consumer_token change oct_token oct_token varchar(170);
   