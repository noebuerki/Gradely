<div class="container">

    <div class="row row-cols-1 row-cols-md-2 pt-5">

        <div class="col-sm">

            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <p class="card-title">Registrierte Benutzer</p>
                    <p class="card-text">
						<?php
						echo htmlentities($UserCount);
						?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm">

            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <p class="card-title">Insgesamt hinterlegte Noten</p>
                    <p class="card-text">
						<?php
						echo htmlentities($GradeCount);
						?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-sm">

            <a href="/admin/usermanager" role="button" class="btn btn-primary w-100 mb-4 align-items-center"
               data-toggle="tooltip" title="Benutzerverwaltung öffnen">
                <div class="card-body">
                    <p class="card-title">Benutzerverwaltung <i class="bi bi-people-fill"></i></p>
                    <i class="bi bi-arrows-fullscreen mt-1"></i>
                </div>
            </a>
        </div>

        <div class="col-sm">

            <a href="/admin/schoolmanager" role="button" class="btn btn-primary w-100 mb-4 align-items-center"
               data-toggle="tooltip" title="Schulverwaltung öffnen">
                <div class="card-body">
                    <p class="card-title">Schulverwaltung <i class="bi bi-archive-fill"></i></p>
                    <i class="bi bi-arrows-fullscreen mt-1"></i>
                </div>
            </a>
        </div>

    </div>
</div>