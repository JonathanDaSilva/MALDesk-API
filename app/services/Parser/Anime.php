<?php
namespace Services\Parser;
use \Dom;
use \Http;

class Anime
{
    public function getList($pseudo)
    {
        $results  = [];
        $response = Http::get("http://myanimelist.net/malappinfo.php?type=anime&status=all&u=$pseudo");
        Dom::load($response);

        Dom::get('anime')->each(function($anime) use (&$results){
            $result = [
                'serie' => [
                    'id'       => (int)    $anime->series_animedb_id->text,
                    'title'    => (string) $anime->series_title->text,
                    'episodes' => (int)    $anime->series_episodes->text,
                    'synonyms' => (array)  explode(';', $anime->series_synonyms->text),
                    'type'     => (int)    $anime->series_type->text,
                    'status'   => (int)    $anime->series_status->text,
                    'image'    => (string) $anime->series_image->text,
                ],
                'user' => [
                    'episodes' => (int)    $anime->my_watched_episodes->text,
                    'score'    => (int)    $anime->my_score->text,
                    'status'   => (int)    $anime->my_status->text,
                ]
            ];
            array_push($results, $result);
        });

        return $results;
    }
}
