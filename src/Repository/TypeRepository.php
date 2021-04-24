<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class TypeRepository extends Repository
{
	protected $tablename = "typ";
	private $GradeRepo;

	function __construct()
	{
		$this->GradeRepo = new GradeRepository();
	}

	/* Datenbank-Funktionen */
	public function getTypesBySchoolSemUser($schoolId, $semester, $userId)
	{
		$query = "SELECT distinct(typID) FROM note WHERE benutzerID = ? AND semester = ? AND fachID IN (SELECT id FROM fach WHERE schuleID = ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('iii', $userId, $semester, $schoolId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	/* Funktionen */
	public function getBestWorstTypes($schoolId, $semester)
	{
		$types = $this->getTypesBySchoolSemUser($schoolId, $semester, $_SESSION['userID']);
		$best = 0;
		$bestValue = 0;
		$worst = 0;
		$worstValue = 7;

		foreach ($types as $type) {
			$average = $this->GradeRepo->getAVG($this->GradeRepo->getGradesByTypeSchoolSem($type->typID, $schoolId, $semester, $_SESSION['userID']));
			if ($average > $bestValue) {
				if ($bestValue < $worstValue && $bestValue != 0) {
					$worst = $best;
					$worstValue = $bestValue;
				}
				$bestValue = $average;
				$best = $type;
			} else if ($average < $worstValue && $average != 0) {
				$worstValue = $average;
				$worst = $type;
			}
		}
		if ($bestValue < 5 && $bestValue != 0 && $worstValue == 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $best->typID, "worstValue" => $bestValue);
		} else if ($bestValue < 5 && $bestValue != 0 && $worstValue != 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $worst->typID, "worstValue" => $worstValue);
		} else if ($worstValue >= 5 && $worstValue != 7 && $bestValue == 0) {
			return array("bestId" => $worst->typID, "bestValue" => $worstValue, "worstId" => 0, "worstValue" => 0);
		} else if ($worstValue >= 5 && $worstValue != 7 && $bestValue != 0) {
			return array("bestId" => $best->typID, "bestValue" => $bestValue, "worstId" => 0, "worstValue" => 0);
		} else if ($bestValue != 0 && $worstValue != 7) {
			return array("bestId" => $best->typID, "bestValue" => $bestValue, "worstId" => $worst->typID, "worstValue" => $worstValue);
		} else if ($bestValue == 0 && $worstValue != 7) {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => $worst->typID, "worstValue" => $worstValue);
		} else if ($bestValue != 0 && $worstValue == 7) {
			return array("bestId" => $best->typID, "bestValue" => $bestValue, "worstId" => 0, "worstValue" => 0);
		} else {
			return array("bestId" => 0, "bestValue" => 0, "worstId" => 0, "worstValue" => 0);
		}
	}
}
