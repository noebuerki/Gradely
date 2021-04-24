<div style="margin-bottom: 100px">

	<?php

	foreach ($schools as $school) {
		echo '
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between mt-5 mb-0">
            <h2 class="m-0">' . htmlentities($school->name) . '</h2>
            
            <div class="mt-2">
            <a data-toggle="collapse" href="#collapseschool' . $school->id . '" role="button" class="btn btn-secondary align-items-center" title="Semester anzeigen">
                    <img src="/images/down-arrow.svg" width="20" height="25" alt="Pfeil nach unten">
            </a>

            <a href="/admin/subject?schoolid=' . htmlentities($school->id) . '" role="button" class="mx-2 btn btn-secondary align-items-center" title="Fach hinzufügen">
                    <img src="/images/add.svg" width="20" height="25" alt="Plus icon">
            </a>
            
            <a href="/admin/removeSchool?schoolid=' . htmlentities($school->id) . '" role="button" class="btn btn-danger align-items-center" title="Schule löschen (inkl. Semester)">
                    <img src="/images/delete.svg" width="20" height="25" alt="Mülleimer icon">
            </a>
            </div>
        </div>
        
        <div class="container collapse" id="collapseschool' . $school->id . '">
    ';

		for ($sem = 1; $sem <= $school->semester; $sem++) {
			$subjects = $subjectsBySchoolAndSemester[$school->id][$sem - 1];

			echo '
        <div class="d-flex align-items-center justify-content-between mx-4 mt-4">
            <h3 class="my-3">Semester ' . $sem . '</h3>
        ';
			echo '
               <a data-toggle="collapse" href="#collapsesemester' . $school->id . $sem . '" role="button" class="btn btn-secondary align-items-center" title="Fächer anzeigen">
                    <img src="/images/down-arrow.svg" width="20" height="25" alt="Pfeil nach unten">
               </a> 
         </div>
         
        <div class="container collapse" id="collapsesemester' . $school->id . $sem . '">
            <div class="row row-cols-2 row-cols-md-4 justify-content-center">
        ';

			if ($subjects != null) {
				foreach ($subjects as $subject) {
					echo '
                <div class="col">
                    <div class="card text-white bg-info mb-3 col align-items-center w-100">
                        <p class="card-title mb-2 mt-4 w-100">' . htmlentities($subject->name) . '</p>
                        <a href="/admin/removeSemesterFromSubject?subjectid=' . htmlentities($subject->id) . '&semester=' . $sem . '" class="mb-4" data-toggle="tooltip" title="Fach entfernen">
                            <img src="/images/delete.svg" width="25" height="25" alt="Mülleimer icon">
                        </a>
                    </div>
                </div>
        ';
				}
			} else {
				echo '
            <div class="col">
                    <div class="card text-white bg-info col align-items-center w-100">
                        <p class="card-title mb-2 mt-2 w-100">Keine Fächer vorhanden</p>
                    </div>
                </div>
                ';
			}


			echo '
            </div>
        </div>
        ';

		}

		echo '
            </div>
        ';
	}
	?>

</div>

<div class="container">
    <div class="row position-fixed fixed-buttonpos">

        <a href="/admin"
           role="button" class="mr-3 btn btn-secondary" data-toggle="tooltip" title="Zurück zum Admin-Panel">
            <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück">
        </a>

        <a href="/admin/school" role="button"
           class="btn btn-secondary" data-toggle="tooltip" title="Schule hinzufügen">
            <img src="/images/add.svg" width="32" height="32" class="my-1" alt="Plus icon">
        </a>

    </div>
</div>