<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require "vendor/autoload.php";

$pdo = ConnectionCreatorFactory::createConnection();

$pdo->beginTransaction();

$student = new Student(null, 'Renato.A', new DateTimeImmutable('1994-01-05'));
$anotherStudent = new Student(null, 'Agatha.O', new DateTimeImmutable('1994-01-20'));

$studentRepository = new PdoStudentRepository($pdo);
$studentRepository->save($student);
$studentRepository->save($anotherStudent);

$pdo->commit();