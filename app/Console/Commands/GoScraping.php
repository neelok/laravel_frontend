<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\UrlScrapper;

class GoScraping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will call the urlscaper function callfordata and populate the data to the respective database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // echo "Hello from scrapper";
        echo (new UrlScrapper)->callfordata();
    }
}
