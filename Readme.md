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
3. Run ```composer install``` on your cmd or terminal.
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
   ```
3. To the _.env_ file save PATHS:
   ```
   #PATHS
   
   DB_PATH_HOST=./databases
   
   APP_PATH_HOST=./Banking-System/app
   
   APP_PATH_CONTAINER=/var/www/html
   ```
4. _docker-compose.yml_ should contain information about images to be saved:
   ```
   version: '3'

   services:
       web:
           build: ./web
           environment:
                - APACHE_RUN_USER=#1000
           volumes:
                - ${APP_PATH_HOST}:${APP_PATH_CONTAINER}
           ports:
                - '8080:80'
           working_dir: ${APP_PATH_CONTAINER}

       db:
          image: mysql:5.7
          container_name: mysql
          command: --default-authentication-plugin=mysql_native_password
          restart: always
          environment:
              MYSQL_DATABASE: laravel
              MYSQL_ROOT_PASSWORD: laravel
              SERVICE_NAME: mysql
          volumes:
              - ${DB_PATH_HOST}:/var/lib/mysql/
      adminer:
          image: adminer
          restart: always
          ports:
              - '6080:8080'

      composer:
          image: composer:1.6
          volumes:
              - ${APP_PATH_HOST}:${APP_PATH_CONTAINER}
          working_dir: ${APP_PATH_CONTAINER}
          command: composer install
     ```
5. Clone project from git with command:
   ```
   $ git clone /link
   ```
6. Next step is to build project:
   ```
   $ docker-compose up --build
   ```
7. Run:
   ```
   $ docker-compose exec web bash
   $ php artisan key:generate
   ```
8. Then create database from localhost and change db data in _.env_ project file.
After that make migrations:
   ```
   $ php artisan migrate
   $ php artisan serve
   ```
9. Open laravel: ```127.0.0.1:8080/public```.