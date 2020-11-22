<?php
use App\Helper;
use App\UserInfo;

require_once APP_ROOT . '/src/Views/Include/header.php';

$counter = 0;
$page = 1;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$slug = '';

?>

    <article class="home-overview">
        <div class="columns-12 center pad-both">
            <div class="titles">
                <h1 class="title">Jouw CodeBank!</h1>
                <h3 class="subtitle">Breid jouw codebank uit!</h3>
            </div>
            <?php
            if(count($data['codes']) == 0){
                echo "Voeg je eerste code snippet! <a href='code/aanmaken' title='CodeBank - Codeaanmaken'>Aanmaken</a>";
            }

            ?>
            <div class="wrapper">
                <?php
                foreach ($data['codes'] as $post) {
                    if ($counter >= ($page - 1) * 5 && $counter < $page * 5) {
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
                    $counter++;
                }
                ?>
            </div>
            <nav aria-label="Page navigation" class="custom-pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?= URL_ROOT . '/blog?page=' . ($page - 1) ?>"
                           tabindex="-1" <?php if ($page == 1) echo 'aria-disabled="true"'; ?>>Previous</a>
                    </li>
                    <?php
                    for ($i = max(1, $page - 2); $i <= floor(count($data['codes']) / 5) + 1; $i++) {
                        if ($i == $page) echo '<li class="page-item active" aria-current="page"><a class="page-link">' .
                            $i . '</a></li>';
                        else echo '<li class="page-item"><a class="page-link" href="' . URL_ROOT . '/code?page=' . $i . '">' . $i . '</a></li>';
                    }
                    ?>
                    <li class="page-item <?php if ($page * 5 > count($data['codes'])) echo 'disabled'; ?>">
                        <a class="page-link"
                           href="<?= URL_ROOT . '/blog?page=' . ($page + 1) ?>" <?php if ($page * 5 > count($data['codes']))
                            echo 'aria-disabled="true"'; ?>>Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </article>

<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>