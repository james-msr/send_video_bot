<?php

namespace App\Utils;

use App\Models\ChannelProfile;
use GuzzleHttp\Client;
use InstagramScraper\Exception\InstagramAuthException;
use InstagramScraper\Exception\InstagramChallengeRecaptchaException;
use InstagramScraper\Exception\InstagramChallengeSubmitPhoneNumberException;
use InstagramScraper\Exception\InstagramException;
use InstagramScraper\Exception\InstagramNotFoundException;
use InstagramScraper\Instagram;
use Phpfastcache\Helper\Psr16Adapter;
use Psr\SimpleCache\InvalidArgumentException;

class GetVideos
{
    /**
     * @return array
     * @throws InstagramAuthException
     * @throws InstagramChallengeRecaptchaException
     * @throws InstagramChallengeSubmitPhoneNumberException
     * @throws InstagramException
     * @throws InstagramNotFoundException
     * @throws InvalidArgumentException
     */
    public static function getVideos(): array
    {
        $instagram = Instagram::withCredentials(new Client(), env('INSTAGRAM_USERNAME'), env('INSTAGRAM_PASSWORD'), new Psr16Adapter('Files'));
        $instagram->setUserAgent('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0');
        $instagram->login();
        $instagram->saveSession();
        $channelProfiles = ChannelProfile::all();
        $videos = [];
        /** @var ChannelProfile $channelProfile */
        foreach ($channelProfiles as $channelProfile) {
            $profile = $channelProfile->profile;
            $medias = $instagram->getMedias($profile->profile_name);
            $lastKey = null;
            foreach ($medias as $media) {
                if ($media->getType() == 'video' && $media->getShortCode() != $channelProfile->last_key) {
                    $videos[] = [
                        'url' => $media->getVideoStandardResolutionUrl(),
                        'caption' => $media->getCaption(),
                        'channel' => $channelProfile->channel->channel_name
                    ];
                    if ($lastKey == null) {
                        $lastKey = $media->getShortCode();
                    }
                }
            }
            if ($lastKey != null) {
                $channelProfile->update([
                    'last_key' => $lastKey
                ]);
            }
        }
        return $videos;
    }
}
