<?php

namespace App\Console\Commands;

use App\Utils\SendVideos;
use Illuminate\Console\Command;

class SendVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        info('sending videos');
        $this->info('sending videos');
        SendVideos::sendVideos();
        $this->info('videos sent');
        info('videos sent');
    }
}
