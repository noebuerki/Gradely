<?php

namespace App\Repository;

use App\DataBase\ConnectionHandler;
use Exception;

require_once '../src/DataBase/ConnectionHandler.php';

class WeightRepository extends Repository
{
	protected $tablename = "gewichtung";
}
