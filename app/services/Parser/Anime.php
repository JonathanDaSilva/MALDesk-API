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

    public function get($id)
    {
        $response = Http::get("http://myanimelist.net/anime/$id");
        Dom::load($response);

        $result = [
            'serie' => [
                'id'        => (int)    $id,
                'title'     => (string) Dom::get('h1')->text,
                'title_eng' => (string) Dom::query('//*[@class="dark_text" and text()="English:"]/..')->content,
                'title_jap' => (string) Dom::query('//*[@class="dark_text" and text()="Japanese:"]/..')->content,
                'synonyms'  => (string) Dom::query('//*[@class="dark_text" and text()="Synonyms:"]/..')->content,
                'ranked'    => (int)    str_replace('#', '', Dom::query('//*[@class="dark_text" and text()="Ranked:"]/..')->content),
                'type'      => (string) Dom::query('//*[@class="dark_text" and text()="Type:"]/..')->content,
                'episodes'  => (int)    Dom::query('//*[@class="dark_text" and text()="Episodes:"]/..')->content,
                'status'    => (int)    $this->StatusNumber(Dom::query('//*[@class="dark_text" and text()="Status:"]/..')->content),
                'genres'    => (array)  explode(', ', trim(str_replace('Genres:', '', Dom::query('//*[@class="dark_text" and text()="Genres:"]/..')->text))),
                'score'     => (float)  Dom::query('//*[@class="dark_text" and text()="Score:"]/..')->content,
                'synopsis'  => (string) preg_replace('/\s+/', ' ', Dom::query('//table//table//*[@valign="top"]')->content),
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
