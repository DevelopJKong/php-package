<?php
require_once './vendor/autoload.php';

use Eclair\Routing\Route;
use Eclair\Routing\Middleware;
use Eclair\Database\Adaptor;

Adaptor::setup('mysql:dbname=phpblog;host=localhost;port=3306;', 'root', '1234');

class HelloMiddleware extends Middleware
{
    public static function process()
    {
        return true;
    }
}

Route::add('get', '/', function () {
    echo "hello world";
},[HelloMiddleware::class]);

Route::add('get', '/posts/{id}', function ($id) {
    if ($post = Adaptor::getAll('SELECT * FROM users WHERE `id` =?', [$id])) {
        return var_dump($post);
    }
    return http_response_code(404);
});

Route::run();