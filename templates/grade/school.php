<div class="d-flex flex-column justify-content-center mt-5">
    <div class="align-self-center">

        <div class="col-sm card bg-light">
            <div class="card-body">

                <form action="/grade/overview" method="get" class="needs-validation" novalidate>

                    <div class="form-group m-0">
                        <label class="h5" for="schoolSelect">Wähle deine Schule</label>
                        <select required class="form-control" id="schoolSelect" name="schoolid"
                                data-toggle="tooltip" title="Schule auswählen"
                                onchange="generateSemester(this.value)">
                            <option value="" selected disabled>Schule *</option>
							<?php
							foreach ($userSchools as $userSchool) {
								if ($userSchool->schuleID == $selectedSchoolId) {
									echo '<option selected value="' . $userSchool->schuleID . '">' . htmlentities($schools[$userSchool->schuleID]) . '</option>';
								} else {
									echo '<option value="' . $userSchool->schuleID . '">' . htmlentities($schools[$userSchool->schuleID]) . '</option>';
								}
							}
							?>
                        </select>
                        <div class="invalid-feedback">Schule aus der Liste wählen</div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="h5" for="schoolSelect">Wähle dein Semester</label>
                        <select required class="form-control" id="semesterSelect" name="semester"
                                data-toggle="tooltip" title="Semester auswählen">
                            <option value="" selected disabled>Semester *</option>
                        </select>
                        <div class="invalid-feedback">Semester aus der Liste wählen</div>
                    </div>

                    <button type="submit" class="btn btn-secondary mt-4" data-toggle="tooltip"
                            title="Auswahl bestätigen">
                        Weiter <i class="bi bi-arrow-right-short"></i>
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    /* Form Validation | Von Bootstrap Dokumentation kopiert */
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            generateSemester(document.getElementById('schoolSelect').value);
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

    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }

    function generateSemester(value) {
        fetch('/school/getSemester', {
            method: 'POST',
            body: JSON.stringify({School: value}),
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            var box = document.getElementById('semesterSelect');
            if (box.length > 0) {
                for (i = box.length - 1; i >= 0; i--) {
                    box.remove(i);
                }
            }
            var sem = 1;
            var option = document.createElement('option');
            option.text = "Semester *";
            option.value = "";
            option.selected = true;
            option.disabled = true;
            box.add(option);
            while (sem <= data) {
                var option = document.createElement('option');
                option.text = "Semester " + sem;
                option.value = sem;
                box.add(option);
                sem = sem + 1;
            }
        });
    }
</script>
