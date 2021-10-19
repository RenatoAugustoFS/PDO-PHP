<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
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
        foreach ($stmt->fetchAll() as $student) {
            $studentList[] = $student = new Student(
                $student['id'],
                $student['name'],
                new \DateTimeImmutable($student['birth_date']));

            //$this->fillPhoneOf($student);
        }
        return $studentList;
    }

    /*
    private function fillPhoneOf(Student $student): void
    {
        N+1
        $sqlFind = ("SELECT id, area_code, number FROM phones WHERE student_id = ?");
        $stmt = $this->pdo->prepare($sqlFind);
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);

        if(!$stmt->execute()){
            return;
        }

        $phonesList = $stmt->fetchAll();
        foreach ($phonesList as $phone){
            $phone = new Phone($phone['id'], $phone['area_code'], $phone['number']);
            $student->addPhone($phone);
        }
    }
    */

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

    public function studentsWithPhone(): array
    {
        $sql = "SELECT students.id, 
        students.name, 
        students.birth_date,
        phones.id AS phone_id, 
        phones.area_code,
        phones.number FROM students
        JOIN phones ON students.id = phones.student_id";

        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll();
        $studentList = [];

        foreach ($result as $row) {
            if(!array_key_exists($row['id'], $studentList)){
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new \DateTimeImmutable($row['birth_date'])
                );
            }

            $phone = new Phone($row['phone_id'], $row['area_code'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }

        return $studentList;
    }
}