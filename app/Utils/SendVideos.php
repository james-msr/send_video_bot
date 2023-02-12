<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use InstagramScraper\Exception\InstagramAuthException;
use InstagramScraper\Exception\InstagramChallengeRecaptchaException;
use InstagramScraper\Exception\InstagramChallengeSubmitPhoneNumberException;
use InstagramScraper\Exception\InstagramException;
use InstagramScraper\Exception\InstagramNotFoundException;
use Psr\SimpleCache\InvalidArgumentException;

class SendVideos
{
    /**
     * @return bool
     * @throws InstagramAuthException
     * @throws InstagramChallengeRecaptchaException
     * @throws InstagramChallengeSubmitPhoneNumberException
     * @throws InstagramException
     * @throws InstagramNotFoundException
     * @throws InvalidArgumentException
     */
    public static function sendVideos(): bool
    {
        $videos = GetVideos::getVideos();
        foreach ($videos as $video) {
            Http::post('https://api.telegram.org/bot' . setting('admin.bot_token') . '/sendVideo', [
                'chat_id' => '@' . $video['channel'],
                'video' => $video['url'],
                'caption' => $video['caption'],
                'parse_mode' => 'html'
            ]);
            sleep(10);
        }
        return true;
    }
}
