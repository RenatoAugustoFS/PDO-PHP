<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

//Static creation method Pattern
class ConnectionCreatorFactory
{
    public static function createConnection(): PDO
    {
        $conection = new PDO('mysql:host=localhost;dbname=PDO-PHP;user=renatodev;password=renatodev');
        $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conection;
    }
}