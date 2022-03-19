<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CommunicateController;

class SendGoodDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deals:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will look thru the data and send the good deals to the emails specified';

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
        // (new CommunicateController)->sendgooddeals();
        (new CommunicateController)->sendgooddeals();
    }
}
