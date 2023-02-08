<?php

namespace App\Utils;

use App\Models\InstaProfile;
use Illuminate\Support\Facades\Http;

class SendVideos
{
    public static function sendVideos()
    {
        $videos = GetVideos::getVideos();
        foreach ($videos as $video) {
            Http::post('https://api.telegram.org/bot' . setting('admin.bot_token') . '/sendVideo', [
                'chat_id' => '@' . setting('admin.channel'),
                'video' => $video['url'],
                'caption' => $video['caption'],
                'parse_mode' => 'html'
            ]);
            sleep(10);
        }
        return true;
    }
}
