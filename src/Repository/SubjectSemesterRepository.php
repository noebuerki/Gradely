<?php

namespace App\Repository;

use App\Authentication\Authentication;
use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class SubjectSemesterRepository extends Repository
{
	protected $tablename = "fachsemester";

	/* Datenbank-Funktionen */
	public function getSemestersBySubject($subjectId)
	{
		$query = "SELECT * FROM $this->tablename WHERE fachID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $subjectId);

		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function addSemesterToSubject($subjectId, $semester)
	{
		$query = "INSERT INTO $this->tablename (fachID, semester) values (?, ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $subjectId, $semester);

		$statement->execute();
	}

	public function removeSemesterFromSubject($subjectId, $semester)
	{
		$query = "DELETE FROM $this->tablename WHERE fachID = ? and semester = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $subjectId, $semester);

		$statement->execute();

	}
}
