<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require "vendor/autoload.php";


try {
    $pdo = ConnectionCreatorFactory::createConnection();
    $studentRepository = new PdoStudentRepository($pdo);
    $students = $studentRepository->studentsWithPhone();
    foreach ($students as $student){
        var_dump($student->phones());
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
