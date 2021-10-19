<?php

require 'vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;

$pdo = ConnectionCreatorFactory::createConnection();

$createTableSql = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );
    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );
';

$pdo->exec($createTableSql);
$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('21', '967218047', 14)");