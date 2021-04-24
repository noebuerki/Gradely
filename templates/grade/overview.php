<div style="margin-bottom: 100px">

    <h2 class="my-5"><?php echo htmlentities($school->name), ' - Semester ', $semester ?></h2>

    <div class="d-flex justify-content-center">
        <div class="card text-white bg-primary mb-4">
            <div class="card-body">
                <p class="card-title mx-3 mt-2">Dein Durchschnitt</p>
				<?php
				echo '<p class="card-text mb-2">' . $average . '</p>';
				?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 justify-content-center">
			<?php

			foreach ($subjectsWithGrades as $subject) {
				echo '
            <div class="col">
                <a href="/grade/subject?subjectid=' . $subject["id"] . '&semester=' . $semester . '" role="button" class="btn btn-info w-100 mb-4">
                    <div class="mb-4 col align-items-center">
                        <i class="bi bi-box-arrow-in-right mt-1 align-self-end" style="margin-left: 98%"></i>
                        <p>' . htmlentities($subject["name"]) . '</p>
                        <p class="mb-4 my-md-4">' . $subject["average"] . '</p>
                    </div>
                </a>
            </div>
                    ';
			}
			?>
        </div>
    </div>

    <a href="/grade" role="button" class="position-fixed fixed-buttonpos btn btn-secondary" data-toggle="tooltip"
       title="Zurück zur Auswahl">
        <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück icon">
    </a>


</div>