<?php

require './vendor/autoload.php';

use App\Business\RssPodcastReaderBusiness;

echo "<pre>";
echo (new RssPodcastReaderBusiness())->getPodcastFile();