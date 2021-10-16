<?php

require 'vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

$name = $argv[1];
$birthDate = $argv[2];
$student = new Student(null, $name, new DateTimeImmutable($birthDate));

$pdo = ConnectionCreatorFactory::createConnection();
$studentRepository = new PdoStudentRepository($pdo);

if($studentRepository->save($student)){
    echo "Success";
}


