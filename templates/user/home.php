<div class="container mb-5">

    <div class="row pt-5">

        <div class="col-sm">

            <div class="card text-white bg-primary mb-3">
                <div class="card-body justify-content-between">
                    <p class="card-title">Dein Gesamtdurchschnitt</p>
					<?php

					if (empty($grades)) {
						echo '<p class="card-text">Nichts gefunden <i class="bi bi-search"></i></p>';
					} else {
						echo '<p class="card-text">' . $average . '</p>';
					}
					?>
                </div>
            </div>
        </div>

        <div class="col-sm">
			<?php

			if (empty($userSchools)) {
				echo '
                        <div class="card text-white bg-info py-md-4">
                            <div class="card-body d-flex flex-row justify-content-between">
                                <p class="card-title m-0">Übersicht noch nicht verfügbar <i class="bi bi-card-text"></i></p>
                            </div>
                        </div>
                    ';
			} else {
				foreach ($schoolGrades as $schoolGrade) {
					if ($schoolGrade["value"] == 0) {
						$schoolGrade["value"] = "-";
					}
					echo '
                    <a href="/grade?schoolid=' . $schoolGrade["id"] . '" data-toggle="tooltip" title="Zur Fächerübersicht von ' . htmlentities($schoolGrade["name"]) . '" role="button" class="mb-3 btn btn-info w-100">
                            <div class="card-body d-flex flex-row justify-content-between my-3">
                                <p class="m-0">' . htmlentities($schoolGrade["name"]) . '</p>
                                <p class="m-0">' . $schoolGrade["value"] . '</p>
                        </div>
                    </a>
                    ';
				}
			}
			?>
        </div>
    </div>
</div>