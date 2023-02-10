<?php

namespace App\Console\Commands;

use App\Services\Interfaces\NewsServiceInterface;
use App\Services\Interfaces\RssServiceInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetAndSaveNewsCommand extends \Illuminate\Console\Command
{
    protected $name = 'News command';

    protected $signature = 'news:get';

    protected $description = 'Command that get news from RSS';

    public function __construct(
        private readonly RssServiceInterface $rssService,
        private readonly NewsServiceInterface $newsService,
    )
    {
        parent::__construct();
    }


    public function run(InputInterface $input, OutputInterface $output): int
    {
        $news = $this->rssService->getNews();
        $this->newsService->save($news);

        return Command::SUCCESS;
    }

}
