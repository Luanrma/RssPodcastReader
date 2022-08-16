<?php
require './vendor/autoload.php';

use App\RssPodcastReader;

(new RssPodcastReader())->rssLoader();