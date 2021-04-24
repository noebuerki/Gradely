<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use App\Authentication\Authentication;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class SchoolRepository extends Repository
{
	protected $tablename = "schule";

	/* Datenbank-Funktionen */
	public function getSchoolByName($schoolName)
	{
		$query = "SELECT * FROM `schule` WHERE name=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('s', $schoolName);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	public function getSchoolsByUser($userId)
	{
		$query = "SELECT * FROM `benutzerschule` WHERE benutzerID=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $userId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function addSchool($name, $semester)
	{
		$schools = $this->readAll();
		$schoolNames = array();
		foreach ($schools as $school) {
			$schoolNames[] = $school->name;
		}

		if (!in_array($name, $schoolNames)) {
			$query = "INSERT INTO $this->tablename (name, semester) values (?, ?)";

			$statement = ConnectionHandler::getConnection()->prepare($query);
			$statement->bind_param('si', $name, $semester);

			$statement->execute();

			return true;
		} else {
			return false;
		}
	}
}
