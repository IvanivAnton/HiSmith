<?php

namespace Tests\Feature;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Testing\Fluent\AssertableJson;

class NewsApiTest extends \Tests\TestCase
{
    public function test_get_news_without_filters()
    {
        /** @var News[] $news */
        $news = News::query()->take(10)->orderBy('publication_datetime', 'desc')->get();
        $countTotal = News::query()->count();

        $response = $this->getJson('api/news')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->where('total', $countTotal)
                ->etc()
            );

        $responseNews = $response->collect('data')->toArray();

        $i = 0;
        foreach ($news as $newsItem) {
            $this->assertEquals($responseNews[$i]['title'], $newsItem->title);
            $this->assertEquals($responseNews[$i]['description'], $newsItem->description);
            $this->assertEquals($responseNews[$i]['image_link'], $newsItem->image_link);
            $this->assertEquals($responseNews[$i]['author'], $newsItem->author);
            $this->assertEquals(Carbon::parse($responseNews[$i]['publication_datetime'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'), $newsItem->publication_datetime->format('Y-m-d H:i:s'));
            ++$i;
        }
    }

    public function test_get_news_pages()
    {
        /** @var News[] $news */
        $news = News::query()->take(10)->offset(10)->orderBy('publication_datetime', 'desc')->get();
        $countTotal = News::query()->count();

        $response = $this->get('api/news?page=2')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->where('total', $countTotal)
                ->etc()
            );
        $responseNews = $response->collect('data')->toArray();
        $i = 0;
        foreach ($news as $newsItem) {
            $this->assertEquals($responseNews[$i]['title'], $newsItem->title);
            $this->assertEquals($responseNews[$i]['description'], $newsItem->description);
            $this->assertEquals($responseNews[$i]['image_link'], $newsItem->image_link);
            $this->assertEquals($responseNews[$i]['author'], $newsItem->author);
            $this->assertEquals(Carbon::parse($responseNews[$i]['publication_datetime'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'), $newsItem->publication_datetime->format('Y-m-d H:i:s'));
            ++$i;
        }

        /** @var News[] $news */
        $news = News::query()->take(10)->offset(20)->orderBy('publication_datetime', 'desc')->get();
        $countTotal = News::query()->count();

        $response = $this->getJson('api/news?page=3')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->where('total', $countTotal)
                ->etc()
            );
        $responseNews = $response->collect('data')->toArray();


        $i = 0;
        foreach ($news as $newsItem) {
            $this->assertEquals($responseNews[$i]['title'], $newsItem->title);
            $this->assertEquals($responseNews[$i]['description'], $newsItem->description);
            $this->assertEquals($responseNews[$i]['image_link'], $newsItem->image_link);
            $this->assertEquals($responseNews[$i]['author'], $newsItem->author);
            $this->assertEquals(Carbon::parse($responseNews[$i]['publication_datetime'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'), $newsItem->publication_datetime->format('Y-m-d H:i:s'));
            ++$i;
        }
    }

    public function test_get_news_order_by()
    {
        $response = $this->getJson('api/news?order_direction=DESC')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->etc()
            );

        $responseCollection = $response->collect('data');
        $this->assertEquals(
            $responseCollection->pluck('id')->toArray(),
            $responseCollection->sortBy('publication_datetime', SORT_REGULAR, true)->pluck('id')->toArray()
        );

        $response = $this->getJson('api/news?order_direction=ASC')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->etc()
            );

        $responseCollection = $response->collect('data');
        $this->assertEquals(
            $responseCollection->pluck('id')->toArray(),
            $responseCollection->sortBy('publication_datetime')->pluck('id')->toArray()
        );

        $response = $this->getJson('api/news')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data')
                ->etc()
            );

        $responseCollection = $response->collect('data');
        $this->assertEquals(
            $responseCollection->pluck('id')->toArray(),
            $responseCollection->sortBy('publication_datetime', SORT_REGULAR, true)->pluck('id')->toArray()
        );
    }

    public function test_get_news_fields()
    {
        /** @var News $news */
        $news = News::query()->latest()->first();

        $this->getJson('api/news?fields[]=title')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.description')
                ->missing('data.0.author')
                ->missing('data.0.image_link')
                ->missing('data.0.publication_datetime')
                ->where('data.0.title', $news->title)
                ->etc()
            );

        $this->getJson('api/news?fields[]=description')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.title')
                ->missing('data.0.author')
                ->missing('data.0.image_link')
                ->missing('data.0.publication_datetime')
                ->where('data.0.description', $news->description)
                ->etc()
            );

        $this->getJson('api/news?fields[]=author')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.title')
                ->missing('data.0.description')
                ->missing('data.0.image_link')
                ->missing('data.0.publication_datetime')
                ->where('data.0.author', $news->author)
                ->etc()
            );

        $this->getJson('api/news?fields[]=image_link')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.title')
                ->missing('data.0.description')
                ->missing('data.0.author')
                ->missing('data.0.publication_datetime')
                ->where('data.0.image_link', $news->image_link)
                ->etc()
            );

        $this->getJson('api/news?fields[]=publication_datetime')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.title')
                ->missing('data.0.description')
                ->missing('data.0.author')
                ->missing('data.0.image_link')
                ->etc()
            );

        $this->getJson('api/news?fields[]=title&fields[]=description')->assertOk()
            ->assertJson(fn(AssertableJson $json) => $json
                ->missing('data.0.author')
                ->missing('data.0.image_link')
                ->missing('data.0.publication_datetime')
                ->where('data.0.title', $news->title)
                ->where('data.0.description', $news->description)
                ->etc()
            );
    }
}
