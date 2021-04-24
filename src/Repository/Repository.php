<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

class Repository
{
	protected $tablename = null;

	/* Datenbank-Funktionen */
	public function readById($id)
	{
		$query = "SELECT * FROM $this->tablename WHERE id=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);

		$statement->execute();

		return $this->processSingleResult($statement->get_result());
	}

	public function readAll($max = 100)
	{
		$query = "SELECT * FROM $this->tablename LIMIT 0, $max";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->execute();

		return $this->processMultipleResults($statement->get_result());
	}

	public function deleteById($id)
	{
		$query = "DELETE FROM $this->tablename WHERE id=?";

		$statement = ConnectionHandler::getConnection()->prepare($query);
		$statement->bind_param('i', $id);

		if (!$statement->execute()) {
			throw new Exception($statement->error);
		}
	}

	/* Funktionen */
	protected function processSingleResult($result)
	{
		if (!$result) {
			throw new Exception($statement->error);
		}

		$row = $result->fetch_object();

		$result->close();

		return $row;
	}

	protected function processMultipleResults($result)
	{
		if (!$result) {
			throw new Exception($statement->error);
		}

		$rows = array();
		while ($row = $result->fetch_object()) {
			$rows[] = $row;
		}

		$result->close();

		return $rows;
	}
}
