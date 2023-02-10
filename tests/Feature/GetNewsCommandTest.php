<?php

namespace Tests\Feature;

use App\Models\Log;
use App\Models\News;
use Tests\TestCase;

class GetNewsCommandTest extends TestCase
{
    public function test_successful_news_receiving(): void
    {
        $this->artisan('news:get')
            ->assertOk();

        $this->assertEquals(Log::query()->count(), 1);

        /** @var Log $log */
        $log = Log::query()->first();
        $this->assertEquals('GET', $log->method);
        $this->assertEquals(config('app.rss_link'), $log->url);
        $this->assertEquals(200, $log->response_code);

        $response = $log->response_body;
        $this->assertEquals(count($response['channel']['item']), News::query()->count());

        foreach ($response['channel']['item'] as $item)
        {
            /** @var News $news */
            $news = News::query()->where('guid', $item['guid'])->first();
            $this->assertNotNull($news);
            $this->assertEquals($item['title'], $news->title);
            $this->assertEquals($item['description'], $news->description);
            $this->assertEquals($item['author'] ?? null, $news->author);
            if(!empty($item['image'])) {
                $this->assertNotNull($news->image_link);
                $this->assertFileExists($news->image_link);
            }
        }
    }
}
