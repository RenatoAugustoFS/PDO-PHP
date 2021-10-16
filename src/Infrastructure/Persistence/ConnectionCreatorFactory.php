<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

//Static creation method Pattern
class ConnectionCreatorFactory
{
    public static function createConnection(): PDO
    {
        $caminhoBanco = __DIR__ . '../../../../banco.sqlite';
        return new PDO('sqlite:' . $caminhoBanco);
    }
}