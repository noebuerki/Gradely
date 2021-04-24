<div class="d-flex flex-column flex-md-row justify-content-center mt-5">

    <div class="align-self-center">
        <div class="col-sm card bg-light">
            <div class="card-body">
                <form action="/admin/addSchool" method="post" class="needs-validation" novalidate>

                    <div class="form-group">
                        <input required type="text" placeholder="Name" class="form-control" id="nameInput"
                               name="nameInput" onfocusout="checkSchool(this.value)" maxlength="45">
                        <p class="invalid-feedback" id="feedback">
                            Ungültiges Format
                        </p>
                    </div>

                    <div class="form-group">
                        <input required type="number" placeholder="Anzahl Semester" class="form-control"
                               name="semesterInput"
                               min="1" max="50">
                        <div class="invalid-feedback">
                            Ungültiges Format
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        Hinzufügen <i class="bi bi-arrow-right-short"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
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
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    function checkSchool(value) {
        fetch('/school/checkSchool', {
            method: 'POST',
            body: JSON.stringify({Name: value}),
        }).then(function (response) {
            return response.json();
        }).then(function (data) {
            let field = document.getElementById("nameInput");
            if (!data) {
                document.getElementById("feedback").innerText = "Name bereits vergeben";
                field.setCustomValidity("Taken");
            } else {
                document.getElementById("feedback").innerText = "Ungültiges Format";
                field.setCustomValidity("");
            }
        });
    }
</script>