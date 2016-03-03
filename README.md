trying to access folder outside of wordpress:
http://wordpress.stackexchange.com/questions/20152/cannot-access-non-wordpress-subdirectories-as-wordpress-overrides-them-with-a-40

<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_URI} ^/subdirectoryname1/(.*)$ [OR]
RewriteCond %{REQUEST_URI} ^/subdirectoryname2/(.*)$ [OR]
RewriteRule ^.*$ - [L]
</IfModule>


Authentication
 username: test@gmail.com
 password: 123

JSON API:
  routes:
    get (/app/api/businesses.php) => all businesses

    get (/app/api/repairCategories.php) => returns cat_id and a cat_name, cat_name is the category
      name you can display, and use the cat_id to post to the server when the user selects the category.

    post (/app/api/repairBusinesses.php)  @param : {"cat_id": 1} => Post to this url with parameter 'cat_id' and it returns business names for that category where the business type is 'repair'

    get (/app/api/reuseCategories.php) => returns cat_id and a cat_name, cat_name is the category
      name you can display, and use the cat_id to post to the server when the user selects the category.

    post (/app/api/reuseBusinesses.php)  @param : {"cat_id": 1} => Post to this url with parameter 'cat_id' and it returns business names for that category where the business type is 'reuse'

    post (/app/api/business.php) @param: {"bus_id"" 1} => Post to this url with parameter 'bus_id' and it returns that businesses.
