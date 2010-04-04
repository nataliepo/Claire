# INSTALLATION NOTES:

- Place oauth/ and tp-libraries/ at the same level on a webserver
- Create the OAuth database according to the definition here: 
      oauth/oauth-php-98/library/store/mysql/mysql.sql
- Create the Users database to remember users' sessions.
      mysql> create table users (user_id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (user_id), user_tp_xid varchar(20), user_name MEDIUMTEXT);
      
      mysql> show columns from users;
      +-------------+-------------+------+-----+---------+----------------+
      | Field       | Type        | Null | Key | Default | Extra          |
      +-------------+-------------+------+-----+---------+----------------+
      | user_id     | int(11)     | NO   | PRI | NULL    | auto_increment |
      | user_tp_xid | varchar(20) | YES  |     | NULL    |                |
      | user_name   | mediumtext  | YES  |     | NULL    |                |
      +-------------+-------------+------+-----+---------+----------------+
      3 rows in set (0.01 sec)
   
- Schema sanity check: you should have all of these tables;

      mysql> show tables;
      +-------------------------+
      | Tables_in_oauth_test    |
      +-------------------------+
      | oauth_consumer_registry |
      | oauth_consumer_token    | 
      | oauth_log               | 
      | oauth_server_nonce      | 
      | oauth_server_registry   | 
      | oauth_server_token      | 
      | users                   | 
      +-------------------------+
      7 rows in set (0.00 sec)
   
- Modify the definition of the OAuth lib's consumer token to support a very long oauth_token value.  TypePad's is longer than what this library gives you by default.

      mysql> alter table oauth_consumer_token change oct_token oct_token varchar(170);
   
- Create a config.php file (if it doesn't already exist), and fill in the following required constants:

      // whatever you'd like to name your local cookie, which will hold the ID (not XID) of 
      // each user's session on your site
      define ('COOKIE_NAME', 'claire-comment-cookie');
      
      // Apply for API keys on http://www.typepad.com/account/access/developer. 
      // These values should be the first two listed under your app's dev keys
      define ('CONSUMER_KEY', 'this comes from typepad');
      define ('CONSUMER_SECRET', 'this comes from typepad');
      
      // This is the CallBack URL in your OAuth dance, which is usually index.php for me.
      define ('CALLBACK_URL', 'http://127.0.0.1/claire/posting_comments/index.php');
      
      // DB connectivity information
      define ('DB_HOST', 'localhost');
      define ('DB_USERNAME', 'user');
      define ('DB_PASSWORD', 'pwd');
      define ('DB_NAME', 'db_name');
      
      // include the tp-libraries/ lib relatively so you only have to include
      // this config.php in your client files
      include_once('../tp-libraries/tp-utilities.php');

- Turn DEFAULT_DEBUG_MODE:
      define ("DEFAULT_DEBUG_MODE", 1);
This will output the API requests and surface additional error info during development.




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
   