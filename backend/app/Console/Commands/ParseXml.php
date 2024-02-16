<?php

namespace App\Console\Commands;

use App\Jobs\ParserJob;
use Illuminate\Console\Command;

class ParseXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:xml {fileName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse the xml with distributors';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $parserJob = new ParserJob($this->argument('fileName'));
        dispatch($parserJob);
    }
}
