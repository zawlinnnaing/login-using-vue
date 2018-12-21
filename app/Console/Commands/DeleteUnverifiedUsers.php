<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:unverified_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all the unverified user in the database';


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
     * @return mixed
     */
    public function handle()
    {
        //
        echo "Hello from cron job";
        User::where('email_verified_at', null)->update(['is_active' => false]);
    }
}
