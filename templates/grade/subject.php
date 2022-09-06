<div style="margin-bottom: 100px">

    <h2 class="my-5"><?php echo $subject->name ?></h2>

    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 justify-content-center">
            <?php
            foreach ($grades as $grade) {
                echo '
            <div class="col">
                <a href="/grade/edit?gradeid=' . $grade->id . '" role="button" class="btn btn-info w-100 mb-4">
                        <div class="mb-4 align-items-center" data-toggle="tooltip" title="Typ: ' . $types[$grade->typID] . 'Gewichtung: ' . $weights[$grade->gewichtungID] . '">
                            <div class="d-flex my-1 justify-content-between">
                                <span>' . $grade->aktualisiert . '</span>
                                <i class="bi bi-pencil-square align-self-end"></i>
                            </div>
                            <p>' . $grade->bemerkung . '</p>
                            <p class="mb-3">' . $grade->wert . '</p>
                        </div>
                    </a>      
            </div>
                          
            ';
            }
            ?>
        </div>
    </div>

    <div class="container">
        <div class="row position-fixed fixed-buttonpos">

            <a href="/grade/overview?schoolid=<?php echo $subject->schuleID ?>&semester=<?php echo $semester ?>"
               role="button" class="mr-3 btn btn-secondary" data-toggle="tooltip" title="Zurück zur Fächerübersicht">
                <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück">
            </a>

            <a href="/grade/add?subjectid=<?php echo $subject->id ?>&semester=<?php echo $semester ?>" role="button"
               class="btn btn-secondary" data-toggle="tooltip" title="Neue Note hinzufügen">
                <img src="/images/add.svg" width="32" height="32" class="my-1" alt="Plus icon">
            </a>

        </div>
    </div>

</div>