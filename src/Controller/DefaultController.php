<?php

namespace App\Controller;

use App\View\View;

class DefaultController
{
	private $errorCodes = array("Dieser Benutzername ist bereits vergeben", "Deine E-Mail ist ungültig", "Dein Benutzername oder Passwort ist falsch", "Das eingegebene Passwort ist falsch", "Diese Schule ist nicht vorhanden", "Dieses Fach ist nicht vorhanden", "Diese Note ist nicht vorhanden", "Dieses Fach wird in diesem Semester nicht unterrichtet", "Diese Schule ist bereits vorhanden", "Dieses Fach ist bereits vorhanden", "Ein unbekannter Fehler ist aufgetreten", "Dieser Benutzername ist nicht gültig", "Dieser Benutzer ist nicht vorhanden", "Dieses Passwort ist ungültig", "Die beiden Passwörter stimmen nicht überein", "Du verfügst nicht über die nötigen Rechte, um diese Seite zu besuchen");

	/* Unsecured Views */
	public function index()
	{
		$controller = new UserController();
		$controller->index();
	}

	public function impressum()
	{
		$view = new View('default/impressum');
		$view->title = 'Impressum';
		$view->heading = 'Impressum';
		$view->display();
	}

	public function data()
	{
		$view = new View('default/datainfo');
		$view->title = 'Datenschutzerklärung';
		$view->heading = 'Datenschutz';
		$view->display();
	}

	public function alert()
	{
		if (!empty($_GET['errorid']) && is_numeric($_GET['errorid']) && !empty($_GET['target']) && is_string($_GET['target'])) {
			if (!strpos($_GET['target'], "javascript")) {
				$target = $_GET['target'];
				$view = new View('default/alert');
				$view->title = 'Info';
				$view->heading = '';
				$view->message = $this->errorCodes[$_GET['errorid'] - 1];
				$view->target = $target;
				$view->display();
			} else {
				header('Location: /default/alert?errorid=11&target=/');
			}
		} else {
			header('Location: /default/alert?errorid=11&target=/');
		}
	}
}
