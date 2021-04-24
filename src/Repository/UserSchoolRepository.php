<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class UserSchoolRepository extends Repository
{
	protected $tablename = "benutzerschule";

	/* Datenbank-Funktionen */
	public function addSchoolToUser($schoolId, $userId)
	{
		$query = "INSERT INTO $this->tablename VALUES (?, ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $userId, $schoolId);

		$statement->execute();
	}

	public function removeSchoolFromUser($schoolId, $userId)
	{
		$query = "DELETE FROM $this->tablename WHERE benutzerID = ? AND schuleID = ?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('ii', $userId, $schoolId);

		$statement->execute();
	}
}
