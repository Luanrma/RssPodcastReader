<?php
require './vendor/autoload.php';

use App\Business\RssPodcastReaderBusiness;

(new RssPodcastReaderBusiness())->rssLoader();