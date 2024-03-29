<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

use App\BaseRepository;
use App\XmlGenerator;

$rssFeed = '<?xml version="1.0" encoding="utf-8"?>';
$rssFeed .= '<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">';
$rssFeed .= '<channel>';
$rssFeed .= '<title>' . TITLE . '</title>';
$rssFeed .= '<link>' . URL_ROOT . '/blog/</link>';
$rssFeed .= '<language>en_US</language>';
$rssFeed .= '<generator>' . URL_ROOT . '</generator>';
$rssFeed .= '<description>' . SUBTITLE . '</description>';
$rssFeed .= '<copyright>Copyright © ' . date("Y") . ' ' . TITLE . '</copyright>';



$baseRepository = new BaseRepository();

$baseRepository->query("SELECT * FROM code ORDER BY id DESC LIMIT :count");
$baseRepository->bind(':count', RSS_COUNTS);
$posts = $baseRepository->fetchAll();
foreach ($posts as $post) {
    $rssFeed .= '<item>';
    $rssFeed .= '<title>' . XmlGenerator::rss($post['title']) . '</title>';
    $rssFeed .= '<category>' . XmlGenerator::rss($post['category']) . '</category>';
    $rssFeed .= '<description>' . XmlGenerator::rss($post['subtitle']) . '</description>';
    $rssFeed .= '<link>' . URL_ROOT . '/blog/' . $post['slug'] . '</link>';
    $rssFeed .= '<pubDate>' . $post['updatedAt'] . '</pubDate>';
    $rssFeed .= '<dc:creator>' . TITLE . '</dc:creator>';
    $rssFeed .= '</item>';
}

$rssFeed .= '</channel>';
$rssFeed .= '</rss>';

file_put_contents("feed/rss.xml", $rssFeed);
header("refresh:0;url=rss.xml");
