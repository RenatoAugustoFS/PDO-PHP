<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use PDO;

class PdoStudentRepository implements StudentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function allStudents(): array
    {
        $sqlFind = "SELECT * FROM students";
        $stmt = $this->pdo->query($sqlFind);
        return $this->hydrateStudentList($stmt);
    }

    public function studentsBirthAt(\DateTimeInterface $birthDate): array
    {
        $sqlFind = "SELECT * FROM students WHERE birth_date = :birth_date";
        $stmt = $this->pdo->prepare($sqlFind);
        $stmt->bindValue('birth_date', $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    private function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentList = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $student) {
            $studentList[] = new Student(
                $student['id'],
                $student['name'],
                new \DateTimeImmutable($student['birth_date']));
        }
        return $studentList;
    }

    private function insert(Student $student): bool
    {
        $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sqlInsert);
        $stmt->bindValue(1, $student->name());
        $stmt->bindValue(2, $student->birthDate()->format('Y-m-d'));

        return $stmt->execute();
    }

    private function update(Student $student): bool
    {
        $sqlUpdate = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id";
        $stmt = $this->pdo->prepare($sqlUpdate);
        $stmt->bindValue('name', $student->name());
        $stmt->bindValue('birth_date', $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue('id', $student->id());

        return $stmt->execute();
    }

    public function save(Student $student): bool
    {
        if($student->id() === null){
            return $this->insert($student);
        }
        return $this->update($student);
    }

    public function remove(int $id): bool
    {
        $sqlRemove = "DELETE FROM students WHERE id = :id";
        $stmt = $this->pdo->prepare($sqlRemove);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return false;
        }
        return true;
    }
}