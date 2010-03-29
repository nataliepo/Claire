# USING Claire's OAUTH
Before you can use the libraries included in this package, you need to:

* Create a database and note its connectivity parameters.  They're hard-coded in index.php and alpha.php right now.
* Insert the tables defined here:
http://github.com/nataliepo/Claire/blob/master/oauth/oauth-php-98/library/store/mysql/mysql.sql . You should end up with 5 oauth tables.
* Correct one of the tables whose column is too skinny:
   alter table oauth_consumer_token change oct_token oct_token varchar(170);
* Hit http://localhost/claire/oauth in your browser.  You should see that you're logged out.  Follow the steps, and you should be sent back to index.php with an active session


# LEFT TO DO
Create a local cookie after successfully authenticating in TypePad, since if you hit index.php without any parameters, it will show that you need to log in again.