<?php

namespace App\Controller;

use App\View\View;
use App\Authentication\Authentication;
use App\Repository\UserRepository;
use App\Repository\GradeRepository;
use App\Repository\SchoolRepository;
use App\Repository\UserSchoolRepository;

class SchoolController
{
	private $UserRepo;
	private $GradeRepo;
	private $SchoolRepo;
	private $UserSchoolRepo;

	function __construct()
	{
		$this->UserRepo = new UserRepository();
		$this->GradeRepo = new GradeRepository();
		$this->SchoolRepo = new SchoolRepository();
		$this->UserSchoolRepo = new UserSchoolRepository();
	}

	/* Views */
	public function index()
	{
		$this->manage();
	}

	public function manage()
	{
		Authentication::restrictAuthenticated();

		$taken = $this->SchoolRepo->getSchoolsByUser($_SESSION['userID']);
		$usedSchools = array();
		foreach ($taken as $used) {
			$usedSchools[] = $this->SchoolRepo->readById($used->schuleID);
		}

		$view = new View('school/manage');
		$view->title = 'Schule';
		$view->heading = 'Schulen verwalten';
		$view->usedSchools = $usedSchools;
		$view->schools = $this->SchoolRepo->readAll();
		$view->display();
	}

	/* Funktionen */
	public function addSchoolToUser()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['schoolSelectAdd']) && is_numeric($_POST['schoolSelectAdd'])) {
			$school = $this->SchoolRepo->readById($_POST['schoolSelectAdd']);
			if ($school != null) {
				$this->UserSchoolRepo->addSchoolToUser($school->id, $_SESSION['userID']);
				header('Location: /');
			} else {
				header('Location: /default/alert?errorid=5&target=/school');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/school');
		}
	}

	public function removeSchoolFromUser()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['schoolSelectRemove']) && is_string($_POST['schoolSelectRemove'])) {
			$school = $this->SchoolRepo->readById($_POST['schoolSelectRemove']);
			if ($school != null) {
				$this->UserSchoolRepo->removeSchoolFromUser($school->id, $_SESSION['userID']);
				$this->GradeRepo->removeGradeBySchoolUser($school->id, $_SESSION['userID']);
				header('Location: /');
			} else {
				header('Location: /default/alert?errorid=5&target=/school');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/school');
		}
	}

	public function checkSchool()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json);
		$school = $this->SchoolRepo->getSchoolByName($data->Name);
		if ($school == null) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}
	}

	public function getSemester()
	{
		Authentication::restrictAuthenticated();

		$json = file_get_contents('php://input');
		$data = json_decode($json);
		$semester = $this->SchoolRepo->readById($data->School)->semester;
		echo json_encode($semester);
	}
}
