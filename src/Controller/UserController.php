<?php

namespace App\Controller;

use App\Repository\GradeRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Repository\TypeRepository;
use App\Repository\SchoolRepository;
use App\Authentication\Authentication;
use App\View\View;

class UserController
{
	private $GradeRepo;
	private $SubjectRepo;
	private $UserRepo;
	private $TypeRepo;
	private $SchoolRepo;

	function __construct()
	{
		$this->GradeRepo = new GradeRepository();
		$this->SubjectRepo = new SubjectRepository();
		$this->UserRepo = new UserRepository();
		$this->TypeRepo = new TypeRepository();
		$this->SchoolRepo = new SchoolRepository();
	}

	/* Unsecured Views */
	public function login()
	{
		$view = new View('user/login');
		$view->title = 'Login';
		$view->heading = 'Login';
		$view->display();
	}

	public function register()
	{
		$view = new View('user/register');
		$view->title = 'Registrieren';
		$view->heading = 'Registrieren';
		$view->display();
	}

	/* Views */
	public function index()
	{
		$this->home();
	}

	public function home()
	{
		Authentication::restrictAuthenticated();

		$subjects = $this->SubjectRepo->getSubjectsByUser($_SESSION['userID']);
		$subjectGrades = array();
		foreach ($subjects as $subject) {
			$subjectGrades[] = $this->GradeRepo->getAVG($this->GradeRepo->getGradesBySubject($subject->id, $_SESSION['userID']));
		}

		$schoolGrades = array();
		foreach ($this->SchoolRepo->getSchoolsByUser($_SESSION['userID']) as $schoolID) {
			$school = $this->SchoolRepo->readById($schoolID->schuleID);
			$subjects = $this->SubjectRepo->getSubjectsBySchool($school->id);
			$grades = array();
			foreach ($subjects as $subject) {
				$grades[] = $this->GradeRepo->getAVG($this->GradeRepo->getGradesBySubject($subject->id, $_SESSION['userID']));
			}
			$schoolAverage = $this->GradeRepo->getAVGNoWeight($grades);
			$schoolGrades[] = array("id" => $school->id, "name" => $school->name, "value" => $schoolAverage);
		}

		$view = new View('user/home');
		$view->title = 'Home';
		$view->heading = 'Hallo ' . htmlentities($this->UserRepo->readById($_SESSION['userID'])->username) . '!';
		$view->userSchools = $this->SchoolRepo->getSchoolsByUser($_SESSION['userID']);
		$view->grades = $this->GradeRepo->getGradesByUser($_SESSION['userID']);
		$view->average = $this->GradeRepo->getAVGNoWeight($subjectGrades);
		$view->schoolGrades = $schoolGrades;
		$view->display();
	}

	public function evaluation()
	{
		Authentication::restrictAuthenticated();

		$view = new View('user/evaluation');
		$view->title = 'Auswertung';
		$view->heading = 'Auswertung';

		$schoolsArray = $this->SchoolRepo->readAll();
		$schools = array();
		foreach ($schoolsArray as $school) {
			$schools[$school->id] = $school->name;
		}

		$view->userSchools = $this->SchoolRepo->getSchoolsByUser($_SESSION['userID']);
		$view->schools = $schools;

		$view->display();
	}

	public function settings()
	{
		Authentication::restrictAuthenticated();

		$view = new View('user/settings');
		$view->title = 'Einstellungen';
		$view->heading = 'Einstellungen';
		$view->user = $this->UserRepo->readById($_SESSION['userID']);
		$view->display();
	}

	/* Funktionen */
	public function doCreate()
	{
		if (isset($_POST['usernameInput']) && is_string($_POST['usernameInput']) && isset($_POST['emailInput']) && is_string($_POST['emailInput']) && isset($_POST['passwordInput']) && is_string($_POST['passwordInput'])) {
			if (preg_match("/[^\' \']+/m", $_POST['usernameInput'])) {
				$userbase = $this->UserRepo->getUserByUsername($_POST['usernameInput']);
				if ($userbase == null) {
					if (filter_var($_POST['emailInput'], FILTER_VALIDATE_EMAIL)) {
						if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}/m', $_POST['passwordInput'])) {
							if ($_POST['passwordInput'] === $_POST['passwordRepeatInput']) {
								$this->UserRepo->addUser($_POST['usernameInput'], $_POST['emailInput'], $_POST['passwordInput']);
								header('Location: /');
							} else {
								header('Location: /default/alert?errorid=15&target=/user/register');
							}
						} else {
							header('Location: /default/alert?errorid=14&target=/user/register');
						}
					} else {
						header('Location: /default/alert?errorid=2&target=/user/register');
					}
				} else {
					header('Location: /default/alert?errorid=1&target=/user/register');
				}
			} else {
				header('Location: /default/alert?errorid=12&target=/user/register');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/user/register');
		}
	}

	public function doLogin()
	{
		if (isset($_POST['usernameInput']) && is_string($_POST['usernameInput']) && isset($_POST['passwordInput']) && is_string($_POST['passwordInput'])) {
			if (Authentication::login($_POST['usernameInput'], $_POST['passwordInput'])) {
				header('Location: /');
			} else {
				header('Location: /default/alert?errorid=3&target=/user/login');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/user/login');
		}
	}

	public function doLogout()
	{
		Authentication::logout();
		header('Location: /');
	}

	public function changeMail()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['passwordInput']) && is_string($_POST['passwordInput']) && isset($_POST['emailInput']) && is_string($_POST['emailInput'])) {
			$user = $this->UserRepo->readById($_SESSION['userID']);
			if (Authentication::login($user->username, $_POST['passwordInput'])) {
				if (filter_var($_POST['emailInput'], FILTER_VALIDATE_EMAIL)) {
					$this->UserRepo->changeMail($_POST['emailInput'], $_SESSION['userID']);
					header('Location: /user/settings');
				} else {
					header('Location: /default/alert?errorid=2&target=/user/settings');
				}
			} else {
				header('Location: /default/alert?errorid=4&target=/user/settings');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/user/settings');
		}
	}

	public function changePassword()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['passwordInput']) && is_string($_POST['passwordInput']) && isset($_POST['passwordInputNew']) && is_string($_POST['passwordInput']) && isset($_POST['passwordRepeatInput']) && is_string($_POST['passwordRepeatInput'])) {
			$user = $this->UserRepo->readById($_SESSION['userID']);
			if (Authentication::login($user->username, $_POST['passwordInput'])) {
				if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}/m', $_POST['passwordInput'])) {
					if ($_POST['passwordInputNew'] == $_POST['passwordRepeatInput']) {
						$this->UserRepo->changePassword($_POST['passwordInputNew'], $_SESSION['userID']);
						Authentication::logout();
						header('Location: /');
					} else {
						header('Location: /default/alert?errorid=15&target=/user/settings');
					}
				} else {
					header('Location: /default/alert?errorid=14&target=/user/settings');
				}
			} else {
				header('Location: /default/alert?errorid=4&target=/user/settings');
			}
		} else {
			header('Location: /');
		}
	}

	public function removeUser()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['passwordInput']) && is_string($_POST['passwordInput'])) {
			$user = $this->UserRepo->readById($_SESSION['userID']);
			if (Authentication::login($user->username, $_POST['passwordInput'])) {
				$this->UserRepo->deleteById($_SESSION['userID']);
				Authentication::logout();
				header('Location: /');
			} else {
				header('Location: /default/alert?errorid=4&target=/user/settings');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/user/settings');
		}
	}

	public function checkUsername()
	{
		$json = file_get_contents('php://input');
		$data = json_decode($json);
		$name = $this->UserRepo->getUserByUsername($data->Username);
		if ($name == null) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}
	}
}
