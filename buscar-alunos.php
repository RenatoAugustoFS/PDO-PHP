<?php

require "vendor/autoload.php";

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

try {
    $pdo = ConnectionCreatorFactory::createConnection();
    $studentRepository = new PdoStudentRepository($pdo);
    $studentsList = $studentRepository->allStudents();
    var_dump($studentsList);
} catch (PDOException $e) {
    echo $e->getMessage();
}



