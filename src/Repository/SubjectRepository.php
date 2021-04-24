<?php

namespace App\Repository;

use App\Repository\GradeRepository;
use App\Authentication\Authentication;
use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class SubjectRepository extends Repository
{
	protected $tablename = "fach";
	private $GradeRepo;

	function __construct()
	{
		$this->GradeRepo = new GradeRepository();
	}

	/* Datenbank-Funktionen */
	public function getSubjectByNameSchool($subjectName, $schoolId)
	{
		$query = "SELECT * FROM $this->tablename WHERE name=? and schuleID=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('si', $subjectName, $schoolId);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	public function getSubjectsByUser($userId)
	{
		$query = "SELECT id FROM $this->tablename WHERE schuleID in (select schuleID from benutzerschule where benutzerID = ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function getSubjectsBySchool($schoolId)
	{
		$query = "SELECT * FROM $this->tablename where schuleID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $schoolId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function getSubjectsBySchoolSem($schoolId, $semesterId)
	{
		$query = "SELECT * FROM $this->tablename join fachsemester as fs on id = fs.fachID where schuleID = ? and semester = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $schoolId, $semesterId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function addSubject($subjectName, $schoolId)
	{
		$subjects = $this->getSubjectsBySchool($schoolId);
		$subjectNames = array();
		foreach ($subjects as $subject) {
			$subjectNames[] = $subject->name;
		}

		if (!in_array($subjectName, $subjectNames)) {
			$query = "INSERT INTO $this->tablename (name, schuleID) values (?, ?)";

			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('si', $subjectName, $schoolId);

			$statement->execute();
		}
	}

	/* Funktionen */
	public function getBestWorstSubjects($schoolId, $semester)
	{
		$subjectIds = $this->getSubjectsBySchoolSem($schoolId, $semester);
		$best = 0;
		$bestValue = 0;
		$worst = 0;
		$worstValue = 7;

		foreach ($subjectIds as $subjectId) {
			$average = $this->GradeRepo->getAVG($this->GradeRepo->getGradesBySubjectSem($subjectId->id, $semester, $_SESSION['userID']));
			if ($average > $bestValue) {
				if ($bestValue < $worstValue && $bestValue != 0) {
					$worst = $best;
					$worstValue = $bestValue;
				}
				$bestValue = $average;
				$best = $subjectId;
			} else if ($average < $worstValue && $average != 0) {
				$worstValue = $average;
				$worst = $subjectId;
			}
		}
		if ($bestValue < 5 && $bestValue != 0 && $worstValue == 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $best->id, "worstValue" => $bestValue);
		} else if ($bestValue < 5 && $bestValue != 0 && $worstValue != 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $worst->id, "worstValue" => $worstValue);
		} else if ($worstValue >= 5 && $worstValue != 7 && $bestValue == 0) {
			return array("bestId" => $worst->id, "bestValue" => $worstValue, "worstId" => 0, "worstValue" => 0);
		} else if ($worstValue >= 5 && $worstValue != 7 && $bestValue != 0) {
			return array("bestId" => $best->id, "bestValue" => $bestValue, "worstId" => 0, "worstValue" => 0);
		} else if ($bestValue != 0 && $worstValue != 7) {
			return array("bestId" => $best->id, "bestValue" => $bestValue, "worstId" => $worst->id, "worstValue" => $worstValue);
		} else if ($bestValue == 0 && $worstValue != 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $worst->id, "worstValue" => $worstValue);
		} else if ($bestValue != 0 && $worstValue == 7) {
			return array("bestId" => $best->id, "bestValue" => $bestValue, "worstId" => 0, "worstValue" => 0);
		} else {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => 0, "worstValue" => 0);
		}
	}
}
