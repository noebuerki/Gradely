<?php

namespace App\Controller;

use App\Repository\GradeRepository;
use App\Repository\SchoolRepository;
use App\Repository\SubjectSemesterRepository;
use App\Repository\TypeRepository;
use App\Repository\WeightRepository;
use App\Repository\SubjectRepository;
use App\View\View;
use App\Authentication\Authentication;

class GradeController
{
	private $GradeRepo;
	private $SchoolRepo;
	private $TypeRepo;
	private $WeightRepo;
	private $SubjectRepo;
	private $SubjectSemesterRepo;

	function __construct()
	{
		$this->GradeRepo = new GradeRepository();
		$this->SchoolRepo = new SchoolRepository();
		$this->TypeRepo = new TypeRepository();
		$this->WeightRepo = new WeightRepository();
		$this->SubjectRepo = new SubjectRepository();
		$this->SubjectSemesterRepo = new SubjectSemesterRepository();
	}

	/* Views */
	public function index()
	{
		$this->school();
	}

	public function school()
	{
		Authentication::restrictAuthenticated();

		$schoolsArray = $this->SchoolRepo->readAll();
		$schools = array();
		foreach ($schoolsArray as $school) {
			$schools[$school->id] = $school->name;
		}

		$view = new View('grade/school');
		$view->title = 'Auswahl';
		$view->heading = 'Notenübersicht wählen';
		$view->userSchools = $this->SchoolRepo->getSchoolsByUser($_SESSION['userID']);
		$view->schools = $schools;

		if (isset($_GET['schoolid'])) {
			$view->selectedSchoolId = $_GET['schoolid'];
		} else {
			$view->selectedSchoolId = null;
		}

		$view->display();
	}

	public function overview()
	{
		Authentication::restrictAuthenticated();

		if (isset($_GET['schoolid']) && is_numeric($_GET['schoolid']) && isset($_GET['semester']) && is_numeric($_GET['semester'])) {
			$school = $this->SchoolRepo->readById($_GET['schoolid']);
			if ($school != null) {
				$semester = $_GET['semester'];

				if ($semester <= $school->semester && $semester > 0) {
					$subjects = $this->SubjectRepo->getSubjectsBySchoolSem($school->id, $semester);
					$grades = array();
					$subjectsWithGrades = array();
					foreach ($subjects as $subject) {
						$average = $this->GradeRepo->getAVG($this->GradeRepo->getGradesBySubjectSem($subject->id, $semester, $_SESSION['userID']));
						$grades[] = $average;
						if ($average == 0) {
							$average = '-';
						}
						$subjectsWithGrades[$subject->id] = array("id" => $subject->id, "name" => $subject->name, "average" => $average);
					}

					$average = $this->GradeRepo->getAVGNoWeight($grades);
					if ($average == 0) {
						$average = '-';
					}

					$view = new View('grade/overview');
					$view->title = 'Fächerübersicht';
					$view->heading = 'Fächerübersicht';
					$view->school = $school;
					$view->average = $average;
					$view->subjects = $subjects;
					$view->subjectsWithGrades = $subjectsWithGrades;
					$view->semester = $semester;

					$view->display();
				} else {
					header('Location: /default/alert?errorid=11&target=/grade');
				}
			} else {
				header('Location: /default/alert?errorid=5&target=/grade');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}

	public function subject()
	{
		Authentication::restrictAuthenticated();

		if (isset($_GET['subjectid']) && is_numeric($_GET['subjectid']) && isset($_GET['semester']) && is_numeric($_GET['semester'])) {
			$subject = $this->SubjectRepo->readById($_GET['subjectid']);
			if ($subject != null) {
				$semester = $_GET['semester'];

				$semsters = array();
				$asignedSemesters = $this->SubjectSemesterRepo->getSemestersBySubject($subject->id);

				if (count($asignedSemesters, COUNT_NORMAL) == 1) {
					$semesters[0] = $asignedSemesters[0]->semester;
				} else if (count($asignedSemesters, COUNT_NORMAL) > 1) {
					foreach ($asignedSemesters as $asignedSemester) {
						$semesters[] = $asignedSemester->semester;
					}
				}

				if (in_array($semester, $semesters)) {
					$types = $this->TypeRepo->readAll();
					$typesArray = array();
					foreach ($types as $type) {
						$typesArray[$type->id] = $type->name;
					}

					$weights = $this->WeightRepo->readAll();
					$weightsArray = array();
					foreach ($weights as $weight) {
						$weightsArray[$weight->id] = $weight->wert;
					}

					$view = new View('grade/subject');
					$view->title = 'Fachübersicht';
					$view->heading = 'Fachübersicht';
					$view->subject = $subject;
					$view->semester = $semester;
					$view->types = $typesArray;
					$view->grades = $this->GradeRepo->getGradesBySubjectSem($subject->id, $semester, $_SESSION['userID']);
					$view->weights = $weightsArray;

					$view->display();
				} else {
					header('Location: /default/alert?errorid=8&target=/grade');
				}
			} else {
				header('Location: /default/alert?errorid=6&target=/grade');
			}

		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}

	public function add()
	{
		Authentication::restrictAuthenticated();

		if (isset($_GET['subjectid']) && is_numeric($_GET['subjectid']) && isset($_GET['semester']) && is_numeric($_GET['semester'])) {
			$subject = $this->SubjectRepo->readById($_GET['subjectid']);
			if ($subject != null) {
				$semester = $_GET['semester'];

				$semsters = array();
				$asignedSemesters = $this->SubjectSemesterRepo->getSemestersBySubject($subject->id);

				if (count($asignedSemesters, COUNT_NORMAL) == 1) {
					$semesters[0] = $asignedSemesters[0]->semester;
				} else if (count($asignedSemesters, COUNT_NORMAL) > 1) {
					foreach ($asignedSemesters as $asignedSemester) {
						$semesters[] = $asignedSemester->semester;
					}
				}

				if (in_array($semester, $semesters)) {
					$view = new View('grade/add');
					$view->title = 'Note hinzufügen';
					$view->heading = 'Note hinzufügen';
					$view->types = $this->TypeRepo->readAll();
					$view->weights = $this->WeightRepo->readAll();
					$view->subject = $subject;
					$view->semester = $semester;
					$view->display();
				} else {
					header('Location: /default/alert?errorid=8&target=/grade');
				}
			} else {
				header('Location: /default/alert?errorid=6&target=/grade');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}

	public function edit()
	{
		Authentication::restrictAuthenticated();

		if (isset($_GET['gradeid']) && is_numeric($_GET['gradeid'])) {
			$grade = $this->GradeRepo->getGradeById($_GET['gradeid'], $_SESSION['userID']);

			if ($grade != null) {
				$view = new View('grade/edit');
				$view->title = 'Note bearbeiten';
				$view->heading = 'Note bearbeiten';
				$view->grade = $grade;
				$view->types = $this->TypeRepo->readAll();
				$view->weights = $this->WeightRepo->readAll();
				$view->subject = $this->SubjectRepo->readById($grade->fachID);
				$view->display();
			} else {
				header('Location: /default/alert?errorid=7&target=/');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}

	/* Funktionen */
	public function addGrade()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['valueInput']) && is_numeric($_POST['valueInput']) && isset($_POST['notesInput']) && is_string($_POST['notesInput']) && isset($_POST['weightSelect']) && is_numeric($_POST['weightSelect']) && isset($_POST['typeSelect']) && is_string($_POST['typeSelect']) && isset($_POST['semester']) && is_numeric($_POST['semester']) && isset($_POST['subjectId']) && is_numeric($_POST['subjectId'])) {
			$weight = $this->WeightRepo->readById($_POST['weightSelect']);
			$type = $this->TypeRepo->readById($_POST['typeSelect']);
			if ($_POST['valueInput'] >= 1 && $_POST['valueInput'] <= 6 && strlen($_POST['notesInput']) <= 30 && $weight != null && $type != null) {
				$subject = $this->SubjectRepo->readById($_POST['subjectId']);
				if ($subject != null) {
					$semsters = array();
					$asignedSemesters = $this->SubjectSemesterRepo->getSemestersBySubject($subject->id);
					if (count($asignedSemesters, COUNT_NORMAL) == 1) {
						$semesters[0] = $asignedSemesters[0]->semester;
					} else if (count($asignedSemesters, COUNT_NORMAL) > 1) {
						foreach ($asignedSemesters as $asignedSemester) {
							$semesters[] = $asignedSemester->semester;
						}
					}
					if (in_array($_POST['semester'], $semesters)) {
						$this->GradeRepo->addGrade($_POST['valueInput'], $_POST['notesInput'], $_POST['semester'], $_POST['subjectId'], $_POST['weightSelect'], $_POST['typeSelect'], $_SESSION['userID']);

						$target = 'Location: /grade/subject?subjectid=' . $_POST['subjectId'] . '&semester=' . $_POST['semester'];
						header($target);
					} else {
						header('Location: /default/alert?errorid=8&target=/grade');
					}
				} else {
					header('Location: /default/alert?errorid=6&target=/grade');
				}
			} else {
				header('Location: /default/alert?errorid=11&target=/grade');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}

	public function updateGrade()
	{
		Authentication::restrictAuthenticated();

		if (isset($_POST['gradeId']) && is_numeric($_POST['gradeId']) && isset($_POST['valueInput']) && is_numeric($_POST['valueInput']) && isset($_POST['notesInput']) && is_string($_POST['notesInput']) && isset($_POST['weightSelect']) && is_numeric($_POST['weightSelect']) && isset($_POST['typeSelect']) && is_string($_POST['typeSelect'])) {
			$grade = $this->GradeRepo->readById($_POST['gradeId']);
			if ($grade != null) {
				$weight = $this->WeightRepo->readById($_POST['weightSelect']);
				$type = $this->TypeRepo->readById($_POST['typeSelect']);
				if ($_POST['valueInput'] >= 1 && $_POST['valueInput'] <= 6 && strlen($_POST['notesInput']) <= 30 && $weight != null && $type != null) {
					$this->GradeRepo->updateGrade($_POST['gradeId'], $_POST['valueInput'], $_POST['notesInput'], $_POST['typeSelect'], $_POST['weightSelect'], $_SESSION['userID']);
					$grade = $this->GradeRepo->getGradeById($_POST['gradeId'], $_SESSION['userID']);

					$target = 'Location: /grade/subject?subjectid=' . $grade->fachID . '&semester=' . $grade->semester;
					header($target);
				} else {
					header('Location: /default/alert?errorid=11&target=/grade');
				}
			} else {
				header('Location: /default/alert?errorid=7?target=/admin');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/grade');
		}
	}

	public function removeGrade()
	{
		Authentication::restrictAuthenticated();

		if (isset($_GET['gradeid']) && is_numeric($_GET['gradeid'])) {
			$grade = $this->GradeRepo->getGradeById($_GET['gradeid'], $_SESSION['userID']);
			if ($grade != null) {
				$this->GradeRepo->removeGrade($grade->id, $_SESSION['userID']);

				$target = 'Location: /grade/subject?subjectid=' . $grade->fachID . '&semester=' . $grade->semester;
				header($target);
			} else {
				header('Location: /default/alert?errorid=7&target=/grade');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/grade');
		}
	}

	public function getSubjectsAndTypesForFrontend()
	{
		Authentication::restrictAuthenticated();

		$json = file_get_contents('php://input');
		$data = json_decode($json);
		$BandWTypes = $this->TypeRepo->getBestWorstTypes($data->School, $data->Semester);
		$BandWSubjects = $this->SubjectRepo->getBestWorstSubjects($data->School, $data->Semester);
		if ($BandWSubjects["bestId"] != 0) {
			$BestSubject = htmlentities($this->SubjectRepo->readById($BandWSubjects["bestId"])->name);
		} else {
			$BestSubject = null;
		}
		if ($BandWSubjects["worstId"] != 0) {
			$WorstSubject = htmlentities($this->SubjectRepo->readById($BandWSubjects["worstId"])->name);
		} else {
			$WorstSubject = null;
		}
		if ($BandWTypes["bestId"] != 0) {
			$BestType = htmlentities($this->TypeRepo->readById($BandWTypes["bestId"])->name);
		} else {
			$BestType = null;
		}
		if ($BandWTypes["worstId"] != 0) {
			$WorstType = htmlentities($this->TypeRepo->readById($BandWTypes["worstId"])->name);
		} else {
			$WorstType = null;
		}
		echo json_encode(array(
			"BestType" => $BestType,
			"BestTypeValue" => $BandWTypes["bestValue"],
			"WorstType" => $WorstType,
			"WorstTypeValue" => $BandWTypes["worstValue"],
			"BestSubject" => $BestSubject,
			"BestSubjectValue" => $BandWSubjects["bestValue"],
			"WorstSubject" => $WorstSubject,
			"WorstSubjectValue" => $BandWSubjects["worstValue"]
		));
	}
}
