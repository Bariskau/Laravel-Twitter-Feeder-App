<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TwitterService;
use Illuminate\Console\Command;
use Atymic\Twitter\Facade\Twitter;
use Laravel\Sanctum\PersonalAccessToken;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:et';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

       $user = User::first();

       foreach ($user->tweets as $tweet) {
           $tweet->update(['status' =>  'passive']);
       }
    }
}
