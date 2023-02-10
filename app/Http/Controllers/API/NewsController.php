<?php

namespace App\Http\Controllers\API;

use App\DTO\GetNewsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetNewsRequest;
use App\Services\Interfaces\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function __construct(private readonly NewsServiceInterface $newsService)
    {
    }

    public function index(GetNewsRequest $request)
    {
        $getNewsDTO = new GetNewsDTO();
        $getNewsDTO->setPage($request->input('page'));
        $getNewsDTO->setOrderDirection(strtolower($request->input('order_direction')));
        $getNewsDTO->setFields($request->input('fields'));

        $news = $this->newsService->getNews($getNewsDTO);

        return new JsonResponse($news);
    }
}
