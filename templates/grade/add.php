<h2 class="mt-5 mb-3"> <?php echo htmlentities($subject->name) ?> </h2>

<div class="d-flex flex-row justify-content-center mb-5">
    <form action="/grade/addGrade" method="post" class="needs-validation" novalidate>

        <div class="form-group">
            <input required type="number" placeholder="Note *" class="form-control" id="valueInput"
                   data-toggle="tooltip" title="Note einfügen"
                   name="valueInput" max="6" min="1" step="0.01">
            <div class="invalid-feedback">
                Ungültiges Format
            </div>
        </div>

        <div class="form-group">
            <input type="text" placeholder="Bemerkung" class="form-control" name="notesInput" maxlength="30"
                   data-toggle="tooltip" title="Bemerkung einfügen">
            <div class="invalid-feedback">
                Ungültiges Format
            </div>
        </div>

        <div class="form-group">
            <select required class="form-control" name="typeSelect" data-toggle="tooltip" title="Typ auswählen"
                    onchange='load_()'>
                <option value="" disabled selected>Typ *</option>
				<?php
				foreach ($types as $type) {
					echo '<option value="' . $type->id . '">' . htmlentities($type->name) . '</option>';
				}
				?>
            </select>
            <div class="invalid-feedback">
                Kein Typ ausgewählt
            </div>
        </div>

        <div class="form-group mb-0">
            <select required class="form-control" name="weightSelect" data-toggle="tooltip"
                    title="Gewichtung auswählen" onchange='load_()'>
                <option value="" disabled selected>Gewichtung *</option>
				<?php

				foreach ($weights as $weight) {
					echo '<option value="' . $weight->id . '">' . htmlentities($weight->wert) . '</option>';
				}
				?>
            </select>
            <div class="invalid-feedback">
                Keine Gewichtung ausgewählt
            </div>
        </div>

        <button type="submit" class="btn btn-secondary mt-3" data-toggle="tooltip" title="Note hinzufügen">
            Hinzufügen <i class="bi bi-arrow-right-short"></i>
        </button>

        <input hidden type="text" name="subjectId" value="<?php echo $subject->id ?>">
        <input hidden type="text" name="semester" value="<?php echo $semester ?>">
    </form>
</div>


<a href="/grade/subject?subjectid=<?php echo $subject->id ?>&semester=<?php echo $semester ?>" role="button"
   class="position-absolute fixed-buttonpos btn btn-secondary"
   data-toggle="tooltip" title="Zurück zur Auswahl">
    <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück icon">
</a>


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