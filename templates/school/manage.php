<div class="d-flex flex-column flex-md-row justify-content-center mt-5 mb-5">

    <div class="card bg-light mb-3 mx-3">
        <div class="card-body">
            <p class="h5 card-title">Schule hinzufügen</p>

            <form action="/school/addSchoolToUser" method="post" class="needs-validation" novalidate>
                <div class="form-group m-0">
                    <select required class="form-control" id="schoolSelectAdd" name="schoolSelectAdd"
                            data-toggle="tooltip" title="Schule auswählen" onchange='load_()'>
                        <option value="" selected disabled>Schule *</option>
						<?php

						foreach ($schools as $school) {
							if (!in_array($school, $usedSchools)) {
								echo '<option value="' . $school->id . '" >' . htmlentities($school->name) . '</option>';
							}
						}
						?>
                    </select>
                    <div class="invalid-feedback">Schule aus der Liste wählen</div>
                </div>

                <button type="submit" class="btn btn-secondary mt-5" data-toggle="tooltip" title="Schule hinzufügen">
                    Hinzufügen <i class="bi bi-arrow-right-short"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="card bg-light mb-3 mx-3">
        <div class="card-body">
            <p class="h5 card-title">Schule entfernen</p>

            <form action="/school/removeSchoolFromUser" method="post" class="needs-validation" novalidate>

                <div class="form-group m-0">
                    <select required class="form-control" id="schoolSelectRemove" name="schoolSelectRemove"
                            data-toggle="tooltip" title="Schule auswählen" onchange='load_()'>
                        <option value="" selected disabled>Schule *</option>
						<?php
						foreach ($usedSchools as $usedSchool) {
							echo '<option value="' . $usedSchool->id . '">' . htmlentities($usedSchool->name) . '</option>';
						}
						?>
                    </select>
                    <div class="invalid-feedback">Schule aus der Liste wählen</div>
                </div>

                <div class="form-group form-check mt-3">
                    <input required type="checkbox" class="form-check-input" id="dataCheckbox" data-toggle="tooltip"
                           title="Bestätigen">
                    <label for="dataCheckbox" class="form-check-label">
                        Alle Noten dieser Schule löschen
                    </label>
                </div>

                <button type="submit" class="btn btn-danger mt-2" data-toggle="tooltip" title="Schule entfernen">
                    Entfernen <i class="bi bi-trash-fill"></i>
                </button>
            </form>
        </div>
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