<?php

class AnimeController extends BaseController
{
    public function index()
    {
        $pseudo = Basic::getUsername();
        return $this->getList($pseudo);
    }

    public function getList($pseudo)
    {
        return Anime::getList($pseudo);
    }

    public function get($id)
    {
        return Anime::get($id);
    }
}
