<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use App\Repository\WeightRepository;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';


class GradeRepository extends Repository
{
	protected $tablename = "note";
	private $WeightRepo;

	function __construct()
	{
		$this->WeightRepo = new WeightRepository();
	}

	/* Datenbank-Funktionen */
	public function getGradeById($gradeId, $userId)
	{
		$query = "SELECT * FROM $this->tablename where id = ? and benutzerID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $gradeId, $userId);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	public function getGradesByUser($userId)
	{
		$query = "SELECT * FROM $this->tablename where benutzerID=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function getGradesBySubject($subjectId, $userId)
	{
		$query = "SELECT * FROM $this->tablename WHERE fachID = ? and benutzerID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $subjectId, $userId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function getGradesBySubjectSem($subjectId, $semester, $userId)
	{
		$query = "SELECT * FROM $this->tablename WHERE fachID = ? and semester = ? and benutzerID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('iii', $subjectId, $semester, $userId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function getGradesByTypeSchoolSem($typeId, $schoolId, $semester, $userId)
	{
		$query = "SELECT * FROM $this->tablename WHERE typID = ? AND semester = ? AND benutzerID = ? AND fachID in (SELECT id FROM fach WHERE schuleID = ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('iiii', $typeId, $semester, $_SESSION['userID'], $schoolId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function addGrade($value, $notes, $semester, $subjectId, $weightId, $typeId, $userId)
	{
		$query = "INSERT INTO $this->tablename (wert, bemerkung, semester, fachID, gewichtungID, typID, benutzerID, aktualisiert) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

		$notes = htmlentities($notes);
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('dsiiiiis', $value, $notes, $semester, $subjectId, $weightId, $typeId, $userId, date("d.m.20y"));

		$statement->execute();
	}

	public function updateGrade($gradeId, $value, $notes, $typeId, $weigthId, $userId)
	{
		$query = "UPDATE $this->tablename SET wert = ?, bemerkung = ?, gewichtungID = ?, typID = ?, aktualisiert = ? WHERE id = ? and benutzerID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('dsiisii', $value, $notes, $weigthId, $typeId, date("d.m.20y"), $gradeId, $userId);

		$statement->execute();
	}

	public function removeGrade($gradeId, $userId)
	{
		$query = "DELETE FROM $this->tablename WHERE id = ? and benutzerID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $gradeId, $userId);

		$statement->execute();
	}

	public function removeGradeBySubjectSem($subjectId, $semester)
	{
		$query = "DELETE FROM $this->tablename WHERE fachID = ? AND semester = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $subjectId, $semester);

		$statement->execute();
	}

	public function removeGradeBySchoolUser($schoolId, $userId)
	{
		$query = "DELETE FROM $this->tablename WHERE benutzerID = ? AND fachID in (SELECT id FROM fach WHERE schuleID = ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $userId, $schoolId);

		$statement->execute();
	}

	public function countGrades()
	{
		$query = "SELECT count(*) AS 'number' FROM note";
		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	/* Funktionen */
	public function getAVG(array $grades)
	{
		$sum = 0;
		$divisor = 0;
		foreach ($grades as $grade) {
			$gewichtung = $this->WeightRepo->readById($grade->gewichtungID)->wert;
			$sum = $sum + ($grade->wert * $gewichtung);
			$divisor = $divisor + $gewichtung;
		}
		if ($divisor == 0) {
			return 0;
		} else {
			return round($sum / $divisor, 2);
		}
	}

	public function getAVGNoWeight(array $grades)
	{
		$sum = 0;
		$divisor = 0;
		foreach ($grades as $grade) {
			if ($grade != 0) {
				$sum = $sum + $grade;
				$divisor++;
			}
		}
		if ($divisor == 0) {
			return 0;
		} else {
			return round($sum / $divisor, 2);
		}
	}
}
