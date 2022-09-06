<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
          content="Gradely ist das revolutionäre Notemanagement-Tool für Schüler, Lernende und Studenten">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

    <title><?= $title; ?> | Gradely</title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
        <button class="navbar-toggler navbar-toggler-button" type="button" data-toggle="collapse"
                data-target="#navbarText" aria-controls="navbarText" aria-expanded="false"
                aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
        </button>
        <a href="/user">
            <img src="/images/logo.png" data-toggle="tooltip" title="Gradely | Home"
                 class="navbar-brand navbar-brand-icon" alt="Gradely Logo">
        </a>

		<?php

		use App\Authentication\Authentication;

		if (Authentication::isAuthenticated()) {
			echo '
        <div class="d-flex flex-row position-absolute navbar-icons">

                <a href="/user/settings" class="mr-3">
                    <img src="/images/avatar.svg" data-toggle="tooltip" title="Mein Profil" width="32" alt="User icon">
                </a>

                <a href="/user/doLogout">
                    <img src="/images/exit.svg" id="logoutBtn" data-toggle="tooltip" title="Ausloggen" width="32" alt="Logout icon">
                </a>
        </div>
        ';
		}
		?>

        <div class="collapse navbar-collapse navbar-item-div" id="navbarText">
            <ul class="navbar-nav mr-auto">
				<?php

				if (Authentication::isAuthenticated()) {
					echo '
                    <li class="nav-item">
                        <a class="nav-link" href="/user">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/evaluation">Auswertung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/grade">Noten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/school">Schule</a>
                    </li>
                ';
				} else {
					echo '
                    <li class="nav-item">
                        <a class="nav-link" href="/user/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/register">Registrieren</a>
                    </li>
                    ';
				}
				?>
                <li class="nav-item">
                    <a class="nav-link" href="/default/impressum">Impressum</a>
                </li>
				<?php
				if (Authentication::isAdmin()) {
					echo '
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin-Panel</a>
                    </li>
                        ';
				}
				?>
            </ul>
        </div>

    </nav>
</header>

<main class="container text-center">
    <h1><?= $heading; ?></h1>
