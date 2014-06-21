<?php
namespace Services\Parser;
use \Dom;
use \Http;
use \Convert;

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
                    'id'       => Convert::toInt   ($anime->series_animedb_id->text),
                    'title'    => Convert::toString($anime->series_title->text),
                    'episodes' => Convert::toString($anime->series_episodes->text),
                    'synonyms' => Convert::toArray ($anime->series_synonyms->text, ';'),
                    'type'     => Convert::toString($anime->series_type->text),
                    'status'   => Convert::toInt   ($anime->series_status->text),
                    'image'    => Convert::toString($anime->series_image->text),
                ],
                'user' => [
                    'episodes' => Convert::toInt   ($anime->my_watched_episodes->text),
                    'score'    => Convert::toInt   ($anime->my_score->text),
                    'status'   => Convert::toInt   ($anime->my_status->text),
                ]
            ];
            array_push($results, $result);
        });

        return $results;
    }

    public function get($id)
    {
        $response = Http::get("http://myanimelist.net/anime/$id");
        Dom::load($response);

        $result = [
            'serie' => [
                'id'        => Convert::toInt   ($id),
                'title'     => Convert::toString(Dom::get('h1')->text),
                'title_eng' => Convert::toString(Dom::query('//*[@class="dark_text" and text()="English:"]/..')->content),
                'title_jap' => Convert::toString(Dom::query('//*[@class="dark_text" and text()="Japanese:"]/..')->content),
                'synonyms'  => Convert::toString(Dom::query('//*[@class="dark_text" and text()="Synonyms:"]/..')->content),
                'ranked'    => Convert::toInt   (Dom::query('//*[@class="dark_text" and text()="Ranked:"]/..')->content),
                'type'      => Convert::toString(Dom::query('//*[@class="dark_text" and text()="Type:"]/..')->content),
                'episodes'  => Convert::toInt   (Dom::query('//*[@class="dark_text" and text()="Episodes:"]/..')->content),
                'status'    => Convert::toInt   ($this->StatusNumber(Dom::query('//*[@class="dark_text" and text()="Status:"]/..')->content)),
                'genres'    => Convert::toArray (str_replace('Genres:', '', Dom::query('//*[@class="dark_text" and text()="Genres:"]/..')->text)),
                'score'     => Convert::toFloat (Dom::query('//*[@class="dark_text" and text()="Score:"]/..')->content),
                'synopsis'  => Convert::toString(Dom::query('//table//table//*[@valign="top"]')->content),
            ]
        ];
        return $result;
    }

    public function statusNumber($text)
    {
        switch($text) {
            case 'Currently Airing':
                return 1;
                break;
            case 'Finished Airing':
                return 2;
                break;
        }
    }
}
