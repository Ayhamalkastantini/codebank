<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

<article class="general-form">
    <div class="columns-12 center pad-both">
        <div class="wrapper">
            <div class="form">
                <div class="form-header">
                    <h1>
                        Code snippet aanmaken
                    </h1>
                </div>
                <div class="form-body">
                    <form id="code-create" data-ajax="false" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                        <div class="form-group row">
                            <label for="category" class="col-sm-3 col-form-label">Categorie</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="category" id="category" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Titel</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subtitle" class="col-sm-3 col-form-label">Ondertitel</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="subtitle" id="subtitle" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-3 col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="image" id="image" required>
                                    <label class="custom-file-label" for="image">Kies een afbeelding</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="subtitle" class="col-sm-3 col-form-label">Bijdragers</label>
                            <div class="col-sm-9">
                                <select class="col-sm-12 form-control"  name="contributors" id="contributors">
                                    <option value="">Geen</option>
                                    <?php
                                        foreach ($data['users'] as $user){
                                            echo '<option value="'.$user->getId().'">'.$user->getEmail().'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="body" class="col-sm-3 col-form-label">Body</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="body" id="body" rows="13" required></textarea>
                            </div>
                        </div>
                    </form>
                    <div class="text-right">
                        <a class="btn btn-primary form-button" id="code-aanmaken-submit">Opslaan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>