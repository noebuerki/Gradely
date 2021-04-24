<div class="d-flex flex-row justify-content-center mt-5">
    <form action="/admin/addSubject" method="post" class="needs-validation" novalidate>

        <div class="form-group">
            <label class="h5" for="nameInput">Wie heisst das Fach?</label>
            <input required type="text" placeholder="Name" class="form-control" id="nameInput" name="nameInput"
                   maxlength="45">
            <div class="invalid-feedback">Ungültiges Format</div>
        </div>

        <div class="form-group mt-5">
            <label class="h5">In welchen Semestern wird es unterrichtet?</label>
			<?php
			$semester = 1;
			while ($semester <= $school->semester) {
				echo '
                <div class="form-check">
                    <input class="form-check-input needs-checking" type="checkbox" value="' . $semester . '" id="semester' . $semester . '" name="semester' . $semester . '">
                    <label class="form-check-label" for="semester' . $semester . '">Semester ' . $semester . '</label>
                </div>

            ';
				$semester++;
			}
			?>
            <div class="d-none text-danger mt-3" id="feedback">
                Wähle mindestens ein Semester
            </div>
        </div>

        <input hidden name="schoolId" value="<?php echo $school->id ?>">

        <button class="mt-5 btn btn-secondary" type="submit">
            Hinzufügen <i class="bi bi-arrow-right-short"></i>
        </button>

    </form>

</div>

<a href="/admin/schoolmanager" role="button" class="position-fixed fixed-buttonpos btn btn-secondary"
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
                    checkCheckBoxes();
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    function checkCheckBoxes() {
        var checkedBoxes = document.querySelectorAll('input[type=checkbox]:checked');

        if (checkedBoxes.length < 1) {
            var checkBoxes = document.querySelectorAll('input[type=checkbox]');
            for (let i = 0; i < checkBoxes.length; i++) {
                let checkBox = document.getElementById(checkBoxes[i].id);
                checkBox.setCustomValidity("Required");
            }
            document.getElementById("feedback").classList.remove("d-none");
        } else {
            var checkBoxes = document.querySelectorAll('input[type=checkbox]');
            for (let i = 0; i < checkBoxes.length; i++) {
                let checkBox = document.getElementById(checkBoxes[i].id);
                checkBox.setCustomValidity("");
            }
        }
    }
</script>