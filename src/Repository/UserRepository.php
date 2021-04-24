<?php

namespace App\Repository;

use App\Authentication\Authentication;
use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class UserRepository extends Repository
{
	protected $tablename = "benutzer";

	/* Datenbank-Funktionen */
	public function getUserByUsername($username)
	{
		$query = "SELECT * FROM $this->tablename WHERE username=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('s', $username);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	public function addUser($username, $email, $password)
	{
		$query = "INSERT INTO $this->tablename (username, email, password) VALUES (?, ?, ?)";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('sss', $username, $email, password_hash($password, PASSWORD_DEFAULT));

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	public function changeMail($mail, $userId)
	{
		$query = "UPDATE $this->tablename SET email=? WHERE id=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('si', $mail, $userId);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	public function changePassword($password, $userId)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);

		$query = "UPDATE $this->tablename SET password=? WHERE id=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('si', $password, $userId);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	public function countUsers()
	{
		$query = "SELECT count(*) AS 'number' FROM $this->tablename";

		$statement = ConnectionHandler::getConnection()->prepare($query);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}
}
