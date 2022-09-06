<h2 class="mt-5 mb-3"> <?php echo htmlentities($subject->name) ?> </h2>

<div class="d-flex flex-column align-items-center mb-5">
    <form action="/grade/updateGrade" method="post" class="needs-validation" novalidate>

        <div class="form-group">
            <input required type="number" placeholder="Note" data-toggle="tooltip" title="Note einfügen"
                   value="<?php echo $grade->wert ?>" class="form-control"
                   name="valueInput" max="6" min="1" step="0.01">
            <div class="invalid-feedback">
                Ungültiges Format
            </div>
        </div>

        <div class="form-group">
            <input required type="text" placeholder="Bemerkung" data-toggle="tooltip" title="Bemerkung einfügen"
                   value="<?php echo htmlentities($grade->bemerkung) ?>"
                   class="form-control"
                   name="notesInput" maxlength="30">
            <div class="invalid-feedback">
                Ungültiges Format
            </div>
        </div>

        <div class="form-group">
            <select required class="form-control" name="typeSelect" data-toggle="tooltip" title="Typ auswählen"
                    onchange='load_()'>
                <option value="" disabled>Typ *</option>
				<?php
				$selected = '';
				foreach ($types as $type) {
					if ($type->id === $grade->typID) {
						$selected = 'selected="selected"';
					}

					echo '<option value="' . $type->id . '" ' . $selected . ' >' . htmlentities($type->name) . '</option>';

					$selected = '';
				}
				?>
            </select>
            <div class="invalid-feedback">
                Kein Typ ausgewählt
            </div>
        </div>

        <div class="form-group mb-0">
            <select required class="form-control" name="weightSelect" data-toggle="tooltip" title="Gewichtung auswählen"
                    onchange='load_()'>
                <option value="" disabled>Gewichtung *</option>
				<?php
				foreach ($weights as $weight) {
					if ($weight->id === $grade->gewichtungID) {
						$selected = 'selected="selected"';
					}

					echo '<option value="' . $weight->id . '" ' . $selected . ' >' . htmlentities($weight->wert) . '</option>';

					$selected = '';
				}
				?>
            </select>
            <div class="invalid-feedback">
                Keine Gewichtung ausgewählt
            </div>
        </div>

        <button type="submit" name="gradeId" value="<?php echo $grade->id ?>" class="btn btn-secondary mt-3"
                data-toggle="tooltip" title="Änderungen übernehmen">
            Aktualisieren <i class="bi bi-arrow-repeat"></i>
        </button>

    </form>
</div>

<div class="container">
    <div class="row position-fixed fixed-buttonpos">

        <a href="/grade/subject?subjectid=<?php echo $subject->id ?>&semester=<?php echo $grade->semester ?>"
           role="button" class="mr-3 btn btn-secondary" data-toggle="tooltip" title="Zurück zur Fächerübersicht">
            <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück">
        </a>

        <a href="/grade/removeGrade?gradeid=<?php echo $grade->id ?>" role="button" class="btn btn-danger"
           data-toggle="tooltip" title="Note löschen">
            <img src="/images/delete.svg" width="32" height="32" class="my-1" alt="Mülleimer icon">
        </a>

    </div>
</div>

<script>
    /* Form Validation | Von Bootstrap Dokumentation kopiert */
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>