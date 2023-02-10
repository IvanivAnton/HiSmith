<?php

namespace App\Services\Interfaces;


use App\DTO\GetNewsDTO;

interface NewsServiceInterface
{
    public function save($news);
    public function getNews(GetNewsDTO $dto);
}
