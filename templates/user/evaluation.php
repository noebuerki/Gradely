<div class="my-5" id="schoolForm">

    <div class="d-flex flex-column justify-content-center mt-5">
        <div class="align-self-center">

            <div class="col-sm card bg-light">
                <div class="card-body">

                    <div class="form-group m-0">
                        <label class="h5" for="schoolSelect">Wähle deine Schule</label>
                        <select class="form-control" id="schoolSelect" name="schoolid"
                                data-toggle="tooltip" title="Schule auswählen"
                                onchange="generateSemester(this.value)">
                            <option value="" disabled selected>Schule *</option>
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
                        <select class="form-control" id="semesterSelect" name="semester"
                                data-toggle="tooltip" title="Semester auswählen">
                            <option value="" disabled selected>Semester *</option>
                        </select>
                        <div class="invalid-feedback">Semester aus der Liste wählen</div>
                    </div>

                    <button onclick="getResults()" class="btn btn-secondary mt-4" data-toggle="tooltip"
                            title="Auswahl bestätigen">
                        Weiter <i class="bi bi-arrow-right-short"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container d-none my-5" id="evaluation">
    <div class="row">

        <div class="col-sm">
            <h2>Hier läuft es gut</h2>

            <div class="card text-white bg-primary mb-2 mb-md-4">
                <div class="card-body justify-content-between">
                    <p class="card-title">
                        <i class="bi bi-award-fill"></i> Deine Bestleistung
                    </p>
                    <p class="card-text" id="bestSubject"></p>
                    <p class="card-text" id="bestSubjectValue"></p>
                </div>
            </div>

            <div class="card text-white bg-info mb-4">
                <div class="card-body justify-content-between">
                    <p class="card-text m-0" id="bestType"></p>
                </div>
            </div>
        </div>

        <div class="col-sm">
            <h2>Daran solltest du arbeiten</h2>

            <div class="card text-white bg-primary mb-2 mb-md-4">
                <div class="card-body justify-content-between">
                    <p class="card-title"><i class="bi bi-pin-fill"></i>Hier läuft es nicht</p>

                    <p class="card-text" id="worstSubject"></p>
                    <p class="card-text" id="worstSubjectValue"></p>
                </div>
            </div>

            <div class="card text-white bg-info mb-4">
                <div class="card-body justify-content-between">
                    <p class="card-text m-0" id="worstType"></p>
                </div>
            </div>
        </div>

    </div>
</div>

<button class="position-fixed d-none fixed-buttonpos btn btn-secondary" data-toggle="tooltip" title="Zurück zur Auswahl"
        id="backButton" onclick="backToForm()">
    <img src="/images/arrow.svg" width="32" height="32" class="my-1" alt="Pfeil nach links, zurück icon">
</button>

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
            option.disabled = true;
            option.selected = true;
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

    function getResults() {
        let schoolId = document.getElementById('schoolSelect').value;
        let semester = document.getElementById('semesterSelect').value;

        if (schoolId != "" && semester != "") {
            fetch('/grade/getSubjectsAndTypesForFrontend', {
                method: 'POST',
                body: JSON.stringify({School: schoolId, Semester: semester}),
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.BestSubjectValue != 0 && data.BestSubject != null) {
                    console.log(data.BestSubjectValue)
                    document.getElementById("bestSubject").innerText = data.BestSubject;
                    document.getElementById("bestSubjectValue").innerText = data.BestSubjectValue;
                    document.getElementById("bestSubjectValue").classList.remove("d-none");
                } else {
                    document.getElementById("bestSubject").innerHTML = 'Nichts gefunden <i class="bi bi-search"></i>';
                    document.getElementById("bestSubjectValue").classList.add("d-none");
                }
                if (data.BestTypeValue != 0 && data.BestType != null) {
                    document.getElementById("bestType").innerText = data.BestType;
                } else {
                    document.getElementById("bestType").innerHTML = 'Keine Details verfügbar <i class="bi bi-card-text"></i>';
                }
                if (data.WorstSubjectValue != 0 && data.WorstSubject != null) {
                    document.getElementById("worstSubject").innerText = data.WorstSubject;
                    document.getElementById("worstSubjectValue").innerText = data.WorstSubjectValue;
                    document.getElementById("worstSubjectValue").classList.remove("d-none");
                } else {
                    document.getElementById("worstSubject").innerHTML = 'Nichts gefunden <i class="bi bi-search"></i>';
                    document.getElementById("worstSubjectValue").classList.add("d-none");
                }
                if (data.WorstTypeValue != 0 && data.WorstType != null) {
                    document.getElementById("worstType").innerText = data.WorstType;
                } else {
                    document.getElementById("worstType").innerHTML = 'Keine Details verfügbar <i class="bi bi-card-text"></i>';
                }
                document.getElementById("schoolForm").classList.add("d-none");
                document.getElementById("evaluation").classList.remove("d-none");
                document.getElementById("backButton").classList.remove("d-none");
            });
        }
    }

    function backToForm() {
        document.getElementById("schoolForm").classList.remove("d-none");
        document.getElementById("evaluation").classList.add("d-none")
        document.getElementById("backButton").classList.add("d-none");
    }
</script>