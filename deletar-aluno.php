<?php

require 'vendor/autoload.php';

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreatorFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

$pdo = ConnectionCreatorFactory::createConnection();
$studentRepository = new PdoStudentRepository($pdo);

$id = $argv[1];

if($studentRepository->remove($id)){
    echo "Success";
}