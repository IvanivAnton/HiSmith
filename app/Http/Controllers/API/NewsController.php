<?php

namespace App\Http\Controllers\API;

use App\DTO\GetNewsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetNewsRequest;
use App\Services\Interfaces\NewsServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class NewsController extends Controller
{
    public function __construct(private readonly NewsServiceInterface $newsService)
    {
    }

    #[OA\Get(
        path: '/api/news',
        operationId: "getNews",
        description: 'Returns 10 news that were received from RSS',
        parameters: [
            (new OA\Parameter(
                name: 'page',
                description: 'Page for pagination',
                in: "query",
                schema: (new OA\Schema(type: 'integer')),
                example: "1"
            )),
            (new OA\Parameter(
                name: 'order_direction',
                description: 'Publication datetime order directions',
                in: "query",
                schema: (new OA\Schema(type: 'string')),
                examples: [
                    new OA\Examples(example: "asc", summary: 'asc'),
                    new OA\Examples(example: "desc", summary: 'desc'),
                ]
            )),
            (new OA\Parameter(
                name: 'fields[]',
                description: 'Fields to receive from data',
                in: "query",
                schema: (new OA\Schema(type: 'array', items: new OA\Items())),
            ))
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Successful news receiving",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "current_page",
                                type: "integer",
                            ),
                            new OA\Property(
                                property: "data",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(
                                            property: "title",
                                            type: "string",
                                            example: "Умер обладатель премий «Гойя» и призов жюри в Каннах Карлос Саура"
                                        ),
                                        new OA\Property(
                                            property: "description",
                                            type: "string",
                                            example: "Режиссеру был 91 год. На его счету более 50 фильмов, многие из которых получали призы Каннского фестиваля и были номинированы на «Оскар»"
                                        ),
                                        new OA\Property(
                                            property: "publication_datetime",
                                            type: "string",
                                            example: "2023-02-10T17:06:47.000000Z"
                                        ),
                                        new OA\Property(
                                            property: "author",
                                            type: "string",
                                            example: "Мир Ландау",
                                            nullable: true
                                        ),
                                        new OA\Property(
                                            property: "image_link",
                                            type: "string",
                                            example: "http://hi-smith.local/storage/2023/02-10/eb9dd4a77b0999e5e13a0e0ad2de0240.jpg"
                                        ),
                                    ]
                                ),
                            ),
                            new OA\Property(
                                property: "first_page_url",
                                type: "string",
                                example: "http://hi-smith.local/api/news?page=1"
                            ),
                            new OA\Property(
                                property: "from",
                                type: "int",
                                example: "1"
                            ),
                            new OA\Property(
                                property: "last_page",
                                type: "int",
                                example: "7"
                            ),
                            new OA\Property(
                                property: "last_page_url",
                                type: "string",
                                example: "http://hi-smith.local/api/news?page=7"
                            ),
                            new OA\Property(
                                property: "next_page_url",
                                type: "string",
                                example: "http://hi-smith.local/api/news?page=2", nullable: true
                            ),
                            new OA\Property(
                                property: "path",
                                type: "string",
                                example: "http://hi-smith.local/api/news"
                            ),
                            new OA\Property(
                                property: "per_page",
                                type: "int",
                                example: "10"
                            ),
                            new OA\Property(
                                property: "prev_page_url",
                                type: "string",
                                example: null,
                                nullable: true,
                            ),
                            new OA\Property(
                                property: "to",
                                type: "int",
                                example: "10"
                            ),
                            new OA\Property(
                                property: "total",
                                type: "int",
                                example: "123"
                            ),
                        ],
                    ),
                ),
            ),
            new OA\Response(
                response: "422",
                description: "Parameters are invalid",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                example: 'The selected order direction is invalid.'
                            ),
                            new OA\Property(
                                property: "errors",
                                properties: [
                                    new OA\Property(
                                        property: "order_direction",
                                        type: "array",
                                        items: new OA\Items(),
                                        nullable: true
                                    ),
                                    new OA\Property(
                                        property: "page",
                                        type: "array",
                                        items: new OA\Items(),
                                        nullable: true
                                    ),
                                    new OA\Property(
                                        property: "fields",
                                        type: "array",
                                        items: new OA\Items(),
                                        nullable: true
                                    )
                                ],
                                type: "object"
                            ),
                        ],
                    ),
                ),
            ),
            new OA\Response(
                response: "429",
                description: "Over 60 request per minute",
            )
        ]
    )]
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
