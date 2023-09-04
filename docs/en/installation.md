# Welcome To Voila CMS

Voila CMS for laravel based on CRUDBooster CRUD Generator, with the most important features web application development. It's easy, flexible, and powerful.

## System Requirement and Basic Technical Knowledge
- Web Server as:
  - Apache 2.4.x or higher with rewrite engine on (mod_rewrite)
  - Nginx 1.11.x or higher
- Database that laravel supports, actually can be:
  - MySQL
  - Postgres
  - SQLite
  - SQL Server
- Composer
- Laravel 7
- Php 7.2 or higher and the extensions:
  - OpenSSL
  - Mbstring
  - Tokenizer
  - FileInfo

## Installation
1. Setting the database configuration, open .env file at project root directory
```
DB_DATABASE=**your_db_name**
DB_USERNAME=**your_db_user**
DB_PASSWORD=**password**
```

2. Change Schema default string length, Add this in Providers/AppServiceProvider.php
```
use Illuminate\Support\Facades\Schema;
in register function:
Schema::defaultStringLength(191);
```

2. Open the terminal, navigate to your laravel project directory.
```php
$ composer require voila_cms/crudbooster
```

3. Run the following command at the terminal
```php
$ php artisan crudbooster:install
```

## Backend URL
```php
/admin/login
```
- default email : superadmin@voila.digital
- default password : 123456

## What's Next
- [How To Create A Module (CRUD)](./how-to-create-module.md)

## Table Of Contents
- [Back To Index](./index.md)
