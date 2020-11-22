<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <article class="general-form">
    <div class="columns-12 center pad-both">
        <div class="wrapper">
            <div class="form">
                <div class="form-header">
                    <h1>
                        Registreren
                    </h1>
                </div>
                <div class="form-body">
                    <form id="register" data-ajax="false">
                        <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password1" class="col-sm-4 col-form-label">Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="password1" id="password1" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password2" class="col-sm-4 col-form-label">Confirm Password</label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="password2" id="password2" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tagline" class="col-sm-4 col-form-label">Tagline</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="tagline" id="tagline" required rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                    <div class="text-right">
                        <a class="btn btn-primary form-button" id="registreren-submit">Register</a>
                    </div>
                    <p class="mt-4 mb-1">
                        A secret key will send to your email to use for authenticated API access in
                        Authorization header after <code>codeBank </code>
                </div>
            </div>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>