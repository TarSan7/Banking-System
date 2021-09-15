# Banking-System
Application that simulates the banking system.

## How to deploy project locally?

### Localhost

1. Clone this project with git command:
   ```
   $ git clone
   ```
2. Go to the folder application using cd command on your cmd or terminal:
   ```
   $ cd ../path
   ```
3. Run ```sudo composer install``` on your cmd or terminal.
4. Copy _.env.example_ file to _.env_ on the root folder.
   You can type ```copy .env.example .env``` if using Windows or ```cp .env.example .env``` if using Ubuntu.
5. Open your _.env file_ and change the database name (DB_DATABASE), username (DB_USERNAME)
   and password (DB_PASSWORD) field correspond to your configuration.
6. Run:
   ```
   $ php artisan key:generate
   $ php artisan migrate
   $ php artisan serve
   ```
7. Go to _localhost_.


### If using Lando

1. Go through 1, 2 previous steps.
2. From project directory serve command:
   ```
   $ lando start
   $ lando composer install
   $ lando artisan migrate
   ```

### If using Docker

1. Create directory. Then create directories _databases_, _web_ and files _.env_, _docker-compose.yml_,
_web/Dockerfile_.
2. To the Dockerfile save next commands:
   ```
   FROM php:7.2-apache

   RUN docker-php-ext-install \
       pdo_mysql \
       && a2enmod \
       rewrite
   
   RUN curl -sS https://getcomposer.org/installer
   ```
3. To the _.env_ file save PATHS:
   ```
   #PATHS
   
   DB_PATH_HOST=./databases
   
   APP_PATH_HOST=./Banking-System/app
   
   APP_PATH_CONTAINER=/var/www/html
   ```
4. Clone project from git with command:
   ```
   $ git clone /link
   ```
5. Next step is to build project:
   ```
   $ docker-compose up --build
   $ docker-compose start
   ```
6. Go to the folder application using cd command on your cmd or terminal:
   ```
   $ cd ../path
   ```
7. Copy _.env.example_ file to _.env_ on the root folder. You can type ```copy .env.example .env``` \
if using Windows or ```cp .env.example .env``` if using Ubuntu.
8. Database creds in .env should be:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel
   DB_USERNAME=root
   DB_PASSWORD=laravel

9. Run:
   ```
   $ php artisan key:generate
   $ php artisan migrate
   $ php artisan serve
   ```