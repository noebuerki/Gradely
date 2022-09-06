<div class="d-flex justify-content-center mt-5">
    <div class="card bg-light mb-3">
        <div class="card-body">

            <form action="/user/doLogin" method="post" class="needs-validation" novalidate>

                <div class="form-group">
                    <input required type="text" placeholder="Benutzername" class="form-control" data-toggle="tooltip"
                           title="Benutzername eingeben"
                           name="usernameInput" id="usernameInput">
                    <div class="invalid-feedback">Benutzername eingeben</div>
                </div>

                <div class="form-group">
                    <input required type="password" placeholder="Passwort" class="form-control" data-toggle="tooltip"
                           title="Passwort eingeben"
                           name="passwordInput" id="passwordInput">
                    <div class="invalid-feedback">Passwort eingeben</div>
                </div>

                <button type="submit" class="btn btn-secondary" data-toggle="tooltip" title="Anmelden">
                    Anmelden <i class="bi bi-arrow-right-short"></i>
                </button>

            </form>
        </div>
    </div>
</div>

<div class="mt-5">
    <a href="/user/register" class="align-self-end" data-toggle="tooltip" title="Zur Registrierung"><p>Noch kein Nutzer?<br>Jetzt
            Registrieren</p></a>
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
                    window.AppInterface.saveLogin(document.getElementById("usernameInput").value, document.getElementById("passwordInput").value)
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    function escapeRegExp(str) {
        return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
    }
</script>