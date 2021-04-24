<?php

namespace App\DataBase;

use App\Exception\DatabaseConnectionException;
use MySQLi;
use RuntimeException;

class ConnectionHandler
{
	private static $connection = null;

	private function __construct()
	{
		// Privater Konstruktor um die Verwendung von getInstance zu erzwingen.
	}

	public static function getConnection()
	{
		// Prüfen ob bereits eine Verbindung existiert
		if (null === self::$connection) {
			$configFile = '../config.php';

			if (!file_exists($configFile)) {
				throw new RuntimeException('❌ Database config file not found');
			}

			$config = require '../config.php';
			$host = $config['database']['host'];
			$username = $config['database']['username'];
			$password = $config['database']['password'];
			$database = $config['database']['database'];

			// Verbindung initialisieren
			self::$connection = new MySQLi($host, $username, $password, $database);
			if (self::$connection->connect_error) {
				$error = self::$connection->connect_error;

				throw new DatabaseConnectionException($error);
			}

			self::$connection->set_charset('utf8');
		}

		// Verbindung zurückgeben
		return self::$connection;
	}
}
