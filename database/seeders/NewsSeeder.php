<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Foundation\Testing\WithFaker;

class NewsSeeder extends \Illuminate\Database\Seeder
{
    use WithFaker;


    public function __construct()
    {
       $this->setUpFaker();
    }

    public function run()
    {
        $count = 50;
        for ($i = 0; $i < $count; ++$i)
        {
            News::query()->create([
                'title' => $this->faker->text(255),
                'description' => $this->faker->text,
                'author' => random_int(0, 1) ? $this->faker->name : null,
                'image_link' => random_int(0, 1) ? $this->faker->imageUrl : null,
                'guid' => $this->faker->uuid,
                'publication_datetime' => $this->faker->dateTime
            ]);
        }
    }
}
