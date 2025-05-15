<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## How to deploy this sample app locally

1. Open command prompt window.

2. Go to your Desktop;

```bash
$ cd Desktop
```

3. Clone the source code on your desktop;

```bash
$ git clone http://10.169.141.8:5050/KarlAngelo/spa_training.git
```

4. Go inside the project folder;

```bash
$ cd spa_training
```

5. Open the project with VS Code;

```bash
$ code .
```

6. Open Terminal inside VS Code.

7. Execute the following command inside the terminal to install necessary PHP dependencies;

```bash
$ composer install
```

8. Create .env file copied from .env.example;

```bash
$ copy .env.example .env
```

9. Edit the .env file;

**Edit DB_DATABASE / DB_USERNAME / DB_PASSWORD*

```bash
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE= <Your Database Name > 
DB_USERNAME=root
DB_PASSWORD=admin
...
```

10. Execute the following command to migrate database;

**You need to have "!! Your Database Name !!" database in your MySQL database server in advance...*


```bash
$ php artisan migrate
```

11. Open another Terminal window and then execute the following command;

Execute the following command inside the terminal to install necessary package libraries;

```bash
$ yarn
```

```bash
$ yarn watch
```

12. Open another Terminal window and then execute the following command;

```bash
$ php artisan key:generate
```

```bash
$ php artisan serve
```

13. Open http://localhost:8000 .




The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
