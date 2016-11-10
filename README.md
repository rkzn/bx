BX test app
===========

# Instructions to run the app:

* clone project
* create MySQL database for app and fill parameters in app/config/parameters.yml 
* run `composer install`
* run `php app/console doctrine:schema:update --force`
* run `php app/console doctrine:fixtures:load`
* run `php app/console server:run 127.0.0.1:8001`
* open http://127.0.0.1:8001/app_dev.php/ in your browser

# Demo:
* http://books.diamond.kazansky.su

    Login / Password: restapi /secretpw

Using the console after installing httpie.org or some other http client
you can run some commands to test the API as well:

    http "http://books.diamond.kazansky.su/api/books" --json -a restapi:secretpw
    http "http://books.diamond.kazansky.su/api/books?offset=10&limit=5" --json -a restapi:secretpw
    
    http "http://books.diamond.kazansky.su/api/books/ranking/per/country.json?country=usa" --json -a restapi:secretpw
    http "http://books.diamond.kazansky.su/api/books/ranking/per/country.json?country=spain&limit=2" --json -a restapi:secretpw
    http "http://books.diamond.kazansky.su/api/books/ranking/per/country.json?country=germany&offset=10" --json -a restapi:secretpw
    
    http "http://books.diamond.kazansky.su/api/books/0001010565" --json -a restapi:secretpw
    http DELETE "http://books.diamond.kazansky.su/api/books/0001010565" --json -a restapi:secretpw
    
    http POST "http://books.diamond.kazansky.su/api/books" --json -a restapi:secretpw < book.json
    http PUT "http://books.diamond.kazansky.su/api/books/0001010565" --json -a restapi:secretpw < book.json
    http PUT "http://books.diamond.kazansky.su/api/books/0001010565" --json -a restapi:secretpw < book.json
    