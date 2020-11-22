<?php
use App\Helper;
use App\UserInfo;

require_once APP_ROOT . '/src/Views/Include/header.php';
?>

<article class="post-detailpage">
    <div class="columns-12 center pad-both">
        <div class="wrapper">
            <div class="block">
                <div class="image">
                    <?php
                    if ($data['currentUser']['id'] === $data['post']['userId']) {
                            ?>
                            <a href="<?= URL_ROOT . '/code/aanpassen/' . $data['post']['slug'] ?>"
                               class="edit-icon badge badge-light">✍️</a>
                            <?php
                        }
                    ?>
                    <?php
                        if (file_exists('./assets/images/' . Helper::slug($data['post']['title'], '-', false) . '.jpg')) {
                            ?>
                            <img src="../assets/images/<?= Helper::slug($data['post']['title'], '-', false)?>.jpg" class="featureImage"
                                 alt="<?= $data['post']['title']; ?>">
                            <?php
                        }
                    ?>
                </div>
                <div class="content">
                    <h1><?= $data['post']['title']; ?></h1>
                    <small class="text-secondary border-left border-right border-secondary mx-2 px-2">
                        <span class="badge badge-secondary mr-2"><?= $data['post']['category']; ?></span>
                        📅 <?= date("Y/m/d H:i", strtotime($data['post']['updatedAt'])); ?> |
                        <a href="mailto:<?= $data['userInfo']['email']; ?>" class="text-dark"
                           data-toggle="tooltip" data-placement="left"
                           title="<?= $data['userInfo']['tagLine']; ?>">😊 <?= substr($data['userInfo']['email'], 0, strpos($data['userInfo']['email'], '@')); ?>
                        </a>
                    </small>
                    <div class="bodyContent">
                        <code>
                            <?= $data['post']['body']; ?>
                        </code>

                    </div>
                    <div class="share">
                        <a href="http://www.facebook.com/sharer.php?u=<?= URL_ROOT . '/blog/' . $data['post']['title'];
                        ?>&title=<?= $data['post']['title']; ?>"
                           target="_blank">
                            <img class="socialIcon" src="../assets/images/social/facebook.png" alt="Facebook">
                        </a>
                        <a href="http://twitter.com/share?url=<?= URL_ROOT . '/blog/' . $data['post']['title'];
                        ?>&text=<?= $data['post']['title']; ?>&hashtags=<?= $data['post']['category']; ?>" target="_blank">
                            <img class="socialIcon" src="../assets/images/social/twitter.png" alt="Twitter">
                        </a>
                        <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?= URL_ROOT . '/blog/' . $data['post']['title'];
                        ?>&title=<?= $data['post']['title']; ?>&source=<?= URL_ROOT ?>" target="_blank">
                            <img class="socialIcon" src="../assets/images/social/linkedin.png" alt="LinkedIn">
                        </a>
                        <a href="http://pinterest.com/pin/create/button/?url=<?= URL_ROOT . '/blog/' . $data['post']['title'];
                        ?>&media=<?= URL_ROOT . '/assets/images/' . Helper::slug($data['post']['title'], '-', false) . '
                    .jpg'?>&description=<?=
                        $data['post']['title']; ?>" target="_blank">
                            <img class="socialIcon" src="../assets/images/social/pinterest.png" alt="Pinterest">
                        </a>
                        <a href="http://www.tumblr.com/share?v=3&u=<?= URL_ROOT . '/blog/' . $data['post']['title'];
                        ?>&t=<?= $data['post']['title']; ?>" target="_blank">
                            <img class="socialIcon" src="../assets/images/social/tumblr.png" alt="Tumblr">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>