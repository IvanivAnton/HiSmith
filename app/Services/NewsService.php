<?php

namespace App\Services;

use App\DTO\GetNewsDTO;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NewsService implements Interfaces\NewsServiceInterface
{
    public function save($news)
    {
        $guids = array_column($news, 'guid');
        /** @var News[] $newsModels */
        $newsWithImagesExisting
            = News::query()->whereIn('guid', $guids)->whereNotNull('image_link')
            ->pluck('image_link', 'guid');

        $insertData = [];
        $now = now();

        foreach ($news as $newsItem) {
            $news = [
                'title' => $newsItem['title'],
                'description' => $newsItem['description'],
                'guid' => $newsItem['guid'],
                'publication_datetime' => Carbon::parse($newsItem['pubDate'])->format('Y-m-d H:i:s'),
                'author' => $newsItem['author'] ?? null,
            ];

            $shouldDownloadImage = !empty($newsItem['enclosure']['@attributes']['url'])
                && !$newsWithImagesExisting->has($newsItem['guid']);

            if ($shouldDownloadImage) {
                $extension = pathinfo($newsItem['enclosure']['@attributes']['url'], PATHINFO_EXTENSION);
                $path = '/' . $now->format('Y') .
                    '/' . $now->format('m-d') .
                    '/' . md5(uniqid('rbc')) .
                    '.' . $extension;
                Storage::put(
                    '/public' . $path,
                    file_get_contents($newsItem['enclosure']['@attributes']['url'])
                );
                $news['image_link'] = config('app.url') . '/storage' . $path;
            } else {
                $news['image_link'] = $newsWithImagesExisting->get($newsItem['guid']);
            }

            $insertData[] = $news;
        }

        News::query()->upsert($insertData, ['guid']);
    }

    public function getNews(GetNewsDTO $dto)
    {
        $fields = [
            'title',
            'description',
            'publication_datetime',
            'author',
            'image_link',
        ];
        $fieldsToSelect = array_intersect($fields, $dto->getFields()) ?: $fields;
        return News::query()
            ->select($fieldsToSelect)
            ->orderBy(column: 'publication_datetime', direction: $dto->getOrderDirection())
            ->paginate(perPage: 10, page: $dto->getPage());
    }
}
