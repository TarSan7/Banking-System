# Banking-System
Application that simulates the banking system.

## How to deploy project locally?

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