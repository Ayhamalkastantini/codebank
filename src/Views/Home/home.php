<?php use App\Helper;
use App\UserInfo;

require_once APP_ROOT . '/src/Views/Include/header.php'; ?>
<article class="header">
    <img src="/assets/images/codebank-header-image.jpg" class="rounded mr-2" alt="<?= TITLE; ?> Logo">
</article>
<article class="home-overview">
    <div class="columns-12 center pad-both">
        <div class="titles">
            <h1 class="title">CodeBank</h1>
            <h3 class="subtitle">Deel je code met iedereen</h3>
        </div>
        <div class="wrapper">
            <?php
            $slug = '';

            foreach ($data['codes'] as $post) {
                ?>
                <div class="block">
                    <?php
                    if ($data['currentUser']['id'] === $post['id']) {
                        ?>
                        <a href="<?= URL_ROOT . '/code/aanpassen/' . $post['slug'] ?>"
                           class="edit-icon badge badge-light">✍️</a>
                        <?php
                    }
                    ?>
                    <a href="<?= URL_ROOT . '/code/' . $post['slug']; ?>" title="" class="image">
                        <?php
                            if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                                ?>
                                <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg"
                                     alt="<?= $post['title']; ?>">
                                <?php
                            }
                        ?>
                    </a>
                    <div class="content">
                        <span class="date">📅 <?= date("Y/m/d H:i", strtotime($post['updatedAt'])); ?></span>
                        <h3><?= $post['title']; ?></h3>
                        <h4><?= $post['subtitle']; ?></h4>
                        <a href="mailto:<?= $data['userInfo']['email']; ?>" class="text-dark"
                           data-toggle="tooltip" data-placement="left"
                           title="<?= $data['userInfo']['tagLine']; ?>">😊 <?= substr($data['userInfo']['email'], 0, strpos($data['userInfo']['email'], '@')); ?>
                        </a>
                        <a class="btn-primary" href="<?= URL_ROOT . '/code/' . $post['slug']; ?>">Lees meer</a>
                    </div>
                </div>
                    <?php
                }
            ?>
        </div>
    </div>
</article>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>