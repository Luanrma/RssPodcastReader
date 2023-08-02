<?php
require './vendor/autoload.php';

use App\Business\RssPodcastReaderBusiness;

$rssPodcastReader = new RssPodcastReaderBusiness();
$rssPodcastReader->rssLoader();

echo "<pre>";
$rssPodcastReader->getPodcastFile();