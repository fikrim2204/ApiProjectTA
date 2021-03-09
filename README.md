# Documentation

## How to run it?
1. Open Terminal
2. write : php -S localhost:8000 -t public

## How to use API?
1. Register : .../register (POST)
2. Login : .../login (POST)

### GET/POST/PUT/DELETE
There is several model exist : User, Room, Class, Procurement, Maintenance, Program Study and Schedule. <br>
4 method to call api is : GET / POST / PUT / DELETE. <br>
how to write : .../{model}/api/v1/json. <br>

example : 
- User <br>
.../user/api/v1/json (POST/GET/PUT/DELETE). <br>
using port : 8888 <br>
in localhost : http:://localhost:8888/user/api/v1/json <br>

# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
