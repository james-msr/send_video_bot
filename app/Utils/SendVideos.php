<?php

namespace App\Utils;

use App\Models\InstaProfile;
use Illuminate\Support\Facades\Http;

class SendVideos
{
    public static function sendVideos()
    {
        $profiles = InstaProfile::all()->pluck('profile_name')->toArray();
        foreach ($profiles as $profile) {
            shell_exec(base_path('bot/venv/bin/python3.10') . ' ' . base_path('bot/main.py') . " $profile");
            $json = file_get_contents(base_path('bot/data.json'));
            $videos = json_decode($json, true);
            foreach ($videos as $video) {
                Http::post('https://api.telegram.org/bot' . setting('admin.bot_token') . '/sendVideo', [
                    'chat_id' => '@' . setting('admin.channel'),
                    'video' => $video['url'],
                    'caption' => $video['caption'],
                    'parse_mode' => 'html'
                ]);
                sleep(10);
            }
        }
        return true;
    }
}
