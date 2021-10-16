<?php

require "vendor/autoload.php";

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

$birthDate = $argv[1];

$pdo = ConnectionCreatorFactory::createConnection();
$studentRepository = new PdoStudentRepository($pdo);

$studentList = $studentRepository->studentsBirthAt(new DateTimeImmutable($birthDate));
var_dump($studentList);