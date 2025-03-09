# CDshop

CDs store

This is a second semester project in web development.
A music cd webshop which sells new and used cds.
Website link: cdharmony.noga.digital

Installation Guide:
In order to install dependencies please run in the command line:
npm install  
Create a config directory in the root directory which will contain
a constants.php file with the following lines:
the following lines:
define('BASE_URL', 'http://localhost/cdharmony');
define('IMAGE_PATH','/src/assets/images/albums/');

#The apis key for the mail server and google reCaptcha and stripe are not share for obvious reasons

In order to install the MySql database:
You shoud update the db_constants.php n the DataAccess directory
in case your server definitions are different.

Go into the sql directory:

1. Copy the createDatabase - views included.sql content into the sql tab
   in phpAdmin and run the script.
2. In the browser run the /test route (it will insert the data)
3. Copy the triggers.php content in the sql tal in php admin and run the script
4. You are ready to go. Enjoy!

Best Regards,
Noga
