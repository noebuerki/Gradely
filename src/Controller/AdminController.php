<?php

namespace App\Controller;

use App\Repository\GradeRepository;
use App\Repository\SchoolRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use App\Authentication\Authentication;
use App\Repository\SubjectSemesterRepository;
use App\View\View;

class AdminController
{

	private $UserRepo;
	private $GradeRepo;
	private $SchoolRepo;
	private $SubjectRepo;
	private $SubjectSemesterRepo;

	function __construct()
	{
		$this->UserRepo = new UserRepository();
		$this->GradeRepo = new GradeRepository();
		$this->SchoolRepo = new SchoolRepository();
		$this->SubjectRepo = new SubjectRepository();
		$this->SubjectSemesterRepo = new SubjectSemesterRepository();
	}

	/* Views */
	public function index()
	{
		$this->overview();
	}

	public function overview()
	{
		Authentication::restrictAdmin();

		$view = new View('admin/overview');
		$view->title = '🍌';
		$view->heading = 'Admin-Panel';
		$view->UserCount = $this->UserRepo->countUsers()->number;
		$view->GradeCount = $this->GradeRepo->countGrades()->number;
		$view->display();
	}

	public function usermanager()
	{
		Authentication::restrictAdmin();

		$view = new View('admin/usermanager');
		$view->title = 'Benutzerverwaltung';
		$view->heading = 'Benutzer verwalten';
		$view->display();
	}

	public function schoolmanager()
	{
		Authentication::restrictAdmin();

		$schools = $this->SchoolRepo->readAll();

		$subjectsBySchoolAndSemester = array();
		foreach ($schools as $school) {
			$subjectsBySchoolAndSemester[$school->id] = array();
			for ($sem = 1; $sem <= $school->semester; $sem++) {
				$subjectsBySchoolAndSemester[$school->id][] = $this->SubjectRepo->getSubjectsBySchoolSem($school->id, $sem);
			}
		}

		$view = new View('admin/schoolmanager');
		$view->title = 'Schulverwaltung';
		$view->heading = 'Schulen verwalten';
		$view->schools = $schools;
		$view->subjectsBySchoolAndSemester = $subjectsBySchoolAndSemester;
		$view->display();
	}

	public function school()
	{
		Authentication::restrictAdmin();

		$view = new View('admin/school');
		$view->title = 'Schule hinzufügen';
		$view->heading = 'Schule hinzufügen';
		$view->schools = $this->SchoolRepo->readAll();
		$view->display();
	}

	public function subject()
	{
		Authentication::restrictAdmin();

		if (!empty($_GET['schoolid']) && is_numeric($_GET['schoolid'])) {
			$school = $this->SchoolRepo->readById($_GET['schoolid']);
			if ($school != null) {
				$view = new View('admin/subject');
				$view->title = 'Fach hinzufügen';
				$view->heading = 'Fach hinzufügen';
				$view->school = $school;
				$view->display();
			} else {
				header('Location: /default/alert?errorid=5&target=/admin');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}


	}

	/* Funktionen */
	public function addSubject()
	{
		Authentication::restrictAdmin();

		if (!empty($_POST['nameInput']) && is_string($_POST['nameInput']) && !empty($_POST['schoolId']) && is_numeric($_POST['schoolId'])) {
			$school = $this->SchoolRepo->readById($_POST['schoolId']);
			if ($school != null) {
				$this->SubjectRepo->addSubject($_POST['nameInput'], $school->id);
				$subject = $this->SubjectRepo->getSubjectByNameSchool($_POST['nameInput'], $school->id);

				$subjectSemester = array();
				$finalSemester = array();
				$asignedSemester = array();
				$asignedSemesterWithSubject = $this->SubjectSemesterRepo->getSemestersBySubject($subject->id);
				for ($semester = 1; $semester <= $school->semester; $semester++) {
					$variable = "semester$semester";
					if (!empty($_POST[$variable]) && is_numeric($_POST[$variable])) {
						$subjectSemester[] = $_POST[$variable];
					}
				}
				if (count($subjectSemester, COUNT_NORMAL) >= 1) {
					foreach ($asignedSemesterWithSubject as $asignedSemesterWithSubject) {
						$asignedSemester[] = $asignedSemesterWithSubject->semester;
					}
					foreach ($subjectSemester as $semester) {
						if (empty($asignedSemester) || !in_array($semester, $asignedSemester)) {
							$finalSemester[] = $semester;
						}
					}

					if (count($finalSemester, COUNT_NORMAL) == 1) {
						$this->SubjectSemesterRepo->addSemesterToSubject($subject->id, $semester);
						header('Location: /admin/schoolmanager');
					} else if (count($finalSemester, COUNT_NORMAL) > 1) {
						foreach ($finalSemester as $semester) {
							$this->SubjectSemesterRepo->addSemesterToSubject($subject->id, $semester);
						}
						header('Location: /admin/schoolmanager');
					} else {
						header('Location: /default/alert?errorid=10&target=/admin/schoolmanager');
					}
				} else {
					header('Location: /default/alert?errorid=11&target=/admin/schoolmanager');
				}
			} else {
				header('Location: /default/alert?errorid=5&target=/admin/schoolmanager');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}

	public function removeSemesterFromSubject()
	{
		Authentication::restrictAdmin();

		if (!empty($_GET['subjectid']) && is_numeric($_GET['subjectid']) && !empty($_GET['semester']) && is_numeric($_GET['semester'])) {
			$subject = $this->SubjectRepo->readById($_GET['subjectid']);
			if ($subject != null) {
				$this->SubjectSemesterRepo->removeSemesterFromSubject($_GET['subjectid'], $_GET['semester']);
				$this->GradeRepo->removeGradeBySubjectSem($_GET['subjectid'], $_GET['semester']);
				header('Location: /admin/schoolmanager');
			} else {
				header('Location: /default/alert?errorid=6&target=/admin/schoolmanager');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}

	public function addSchool()
	{
		Authentication::restrictAdmin();

		if (!empty($_POST['nameInput']) && is_string($_POST['nameInput']) && !empty($_POST['semesterInput']) && is_numeric($_POST['semesterInput'])) {
			if ($this->SchoolRepo->addSchool($_POST['nameInput'], $_POST['semesterInput'])) {
				header('Location: /admin/schoolmanager');
			} else {
				header('Location: /default/alert?errorid=9&target=/admin/schoolmanager');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}

	public function removeSchool()
	{
		Authentication::restrictAdmin();

		if (isset($_GET['schoolid']) && is_numeric($_GET['schoolid'])) {
			if ($this->SchoolRepo->readById($_GET['schoolid']) != null) {
				$this->SchoolRepo->deleteById($_GET['schoolid']);
				header('Location: /admin/schoolmanager');
			} else {
				header('Location: /default/alert?errorid=5&target=/admin');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}

	public function changePassword()
	{
		Authentication::restrictAdmin();

		if (isset($_POST['usernameInputPW']) && is_string($_POST['usernameInputPW']) && isset($_POST['passwordInputNew']) && is_string($_POST['passwordInputNew']) && isset($_POST['passwordRepeatInput']) && is_string($_POST['passwordRepeatInput'])) {
			$user = $this->UserRepo->getUserByUsername($_POST['usernameInputPW']);
			if ($user != null) {
				if (preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}/m', $_POST['passwordInputNew'])) {
					if ($_POST['passwordInputNew'] == $_POST['passwordRepeatInput']) {
						$this->UserRepo->changePassword($_POST['passwordInputNew'], $user->id);
						header('Location: /admin/usermanager');
					} else {
						header('Location: /default/alert?errorid=15&target=/admin/usermanager');
					}
				} else {
					header('Location: /default/alert?errorid=14&target=/admin/usermanager');
				}
			} else {
				header('Location: /default/alert?errorid=13&target=/admin/usermanager');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}

	public function removeUser()
	{
		Authentication::restrictAdmin();

		if (isset($_POST['usernameInputRemove']) && is_string($_POST['usernameInputRemove'])) {
			$user = $this->UserRepo->getUserByUsername($_POST['usernameInputRemove']);
			if ($user != null) {
				$this->UserRepo->deleteById($user->id);
				header('Location: /admin/usermanager');
			} else {
				header('Location: /default/alert?errorid=13&target=/admin/usermanager');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/admin');
		}
	}
}
