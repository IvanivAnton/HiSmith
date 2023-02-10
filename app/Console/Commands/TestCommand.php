<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends \Illuminate\Console\Command
{
    protected $name = "Test command";
    protected $description = "Command that migrate db fresh and starts tests";
    protected $signature = 'test:with-db-clear';

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->call('migrate:fresh', ['--seed']);
        $this->call('test');

        return Command::SUCCESS;
    }

}
