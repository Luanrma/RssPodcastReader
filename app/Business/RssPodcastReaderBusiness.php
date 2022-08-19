<?php

namespace App\Business;

use App\Traits\FileManipulatorTrait;
use App\Traits\StringTraits;

class RssPodcastReaderBusiness
{
    use StringTraits, FileManipulatorTrait;

    private $podcastList = [];

    public function rssLoader(): void
    {
        $feeds = array(
            "nerdcast" => "https://jovemnerd.com.br/feed-nerdcast/",
            "gugacast" => "https://www.omnycontent.com/d/playlist/0009b167-0e82-414e-91eb-aae8011fc66d/4f6d62c7-be7a-4d80-bc62-ace2013528cb/81c945fb-1467-45ed-b193-ace2013528de/podcast.rss",
            "theapologyline" => "https://rss.art19.com/apology-line",
            "99vidas" => "http://99vidas.com.br/feed.xml",
            "naruhodo" => "https://feeds.simplecast.com/hwQVm5gy",
            "dragoesDeGaragem" => "https://dragoesdegaragem.com/feed/",
            "rapaduraCast" => "https://www.cinemacomrapadura.com.br/rapaduracast.xml"
        );
        
        $entries = array();

        foreach($feeds as $key => $feed) {
            $xml = simplexml_load_file($feed);
           
            foreach($xml->channel->item as $item) {
                $itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
                $item->itunes->image = $itunes->image->attributes();
            }

            $entries = array_merge($entries, $xml->xpath("//channel"));
        }

        $this->createPodcastFiles($entries);
        $this->createPodcastListCatalog();
    }
    
    private function createPodcastFiles(array $entries): void
    {
        foreach($entries as $entry) {
            $podcastName = self::removeAllSpecialCharacaters(strtolower($entry->title));
            $podcastPathName = str_replace(' ', '', 'podcasts/'.$podcastName);
            $podcastDirectoryExists = is_dir($podcastPathName);
            
            if (!$podcastDirectoryExists) {
                mkdir($podcastPathName);
            }

            $entry['podcastName'] = 'podcast-name-'.(string) $podcastName;
            $path = str_replace(' ', '', $podcastPathName.'/'.$podcastName.'.json');

            self::createFile($path, json_encode($entry));
            
            array_push($this->podcastList, [
                'podcastName' => 'podcast-name-'.(string) $podcastName,
                'path' => (string) $path,
                'image' => (string) $entry->image->url,
                'title' => (string) $entry->title,
                'description' => (string) $entry->description
                ]
            );
        }
    }

    private function createPodcastListCatalog(): void
    {
        self::createFile('podcasts/podcastList.json', json_encode($this->podcastList));
    }

    public function getPodcastFile()
    {
        $json = json_decode(file_get_contents('podcasts/podcastList.json'));
        $podcastItens = [];
        foreach($json as $item) {
            
            array_push($podcastItens, [
                'title' => $item->title, 
                'description' => $item->description, 
                'image' => $item->image,
                'podcastName' => $item->podcastName
            ]);
        }

        return json_encode($podcastItens);
    }

    public function selectPodcast(string $podcastName)
    {
        $json = json_decode(file_get_contents('podcasts/podcastList.json'));
        foreach($json as $item) {
            if ($item->podcastName == $podcastName) {
                return json_encode(file_get_contents($item->path));
            }
        }
    }
}