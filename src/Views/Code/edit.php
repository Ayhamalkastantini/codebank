<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>
    <article class="general-form">
        <div class="columns-12 center pad-both">
            <div class="wrapper">
                <div class="form">
                    <div class="form-header">
                        <h1>
                            Update code
                        </h1>
                    </div>
                    <div class="form-body">
                        <form id="code-update" data-ajax="false" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                            <input type="hidden" name="id" value="<?= $data['post']['id']; ?>">
                            <div class="form-group row">
                                <label for="category" class="col-sm-3 col-form-label">Category</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="category" id="category"
                                           value="<?= $data['post']['category'] ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-sm-3 col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="<?= $data['post']['title'] ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="subtitle" class="col-sm-3 col-form-label">Subtitle</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="subtitle" id="subtitle"
                                           value="<?= $data['post']['subtitle'] ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="image" id="image">
                                        <label class="custom-file-label" for="image">Choose feature image</label>
                                    </div>
                                </div>
                            </div>
                            <?php
                                if($data['codeRights']['kind'] == 0){
                                    ?>
                                    <div class="form-group row">
                                        <label for="subtitle" class="col-sm-3 col-form-label">Bijdragers</label>
                                        <div class="col-sm-9">
                                            <select class="col-sm-12 form-control"  name="contributors" id="contributors">
                                                <option value="">Geen</option>
                                                <?php
                                                foreach ($data['users'] as $user){
                                                    if($data['contributors']['id'] == $user->getId()){
                                                        echo '<option value="'.$user->getId().'" selected>'.$user->getEmail().'</option>';
                                                    }else{
                                                        echo '<option value="'.$user->getId().'">'.$user->getEmail().'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                            <div class="form-group row">
                                <label for="body" class="col-sm-3 col-form-label">Body</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="body" id="body" rows="13" required><?= str_replace('&', '&amp;', $data['post']['body']) ?></textarea>
                                </div>
                            </div>
                        </form>
                        <div class="text-right">
                            <a class="btn btn-primary form-button" id="code-aanpassen-submit">Aanpassen</a>
                            <a class="btn btn-danger text-light form-delete-button" id="<?= $data['post']['slug'] ?>">Verwijderen</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>