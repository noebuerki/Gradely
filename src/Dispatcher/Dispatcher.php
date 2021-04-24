<?php

namespace App\Dispatcher;

class Dispatcher
{
	public static function dispatch()
	{
		session_start();
		$controllerName = UriParser::getControllerName() . 'Controller';
		$className = 'App\\Controller\\' . $controllerName;
		$methodName = UriParser::getMethodName();

		// Eine neue Instanz des Controllers wird erstellt und die gewünschte
		// Methode darauf aufgerufen.
		$controller = new $className();
		$controller->$methodName();
	}
}
