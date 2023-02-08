<?php

namespace App\Utils;

use App\Models\InstaProfile;

class GetVideos
{
    public static function getVideos()
    {
        $instagram = \InstagramScraper\Instagram::withCredentials(new \GuzzleHttp\Client(), 'james_msr0323', 'saidbaevm23', new \Phpfastcache\Helper\Psr16Adapter('Files'));
        $instagram->login();
        $profiles = InstaProfile::all();
        $videos = [];
        /** @var InstaProfile $profile */
        foreach ($profiles as $profile) {
            $medias = $instagram->getMedias($profile->profile_name);
            foreach ($medias as $media) {
                if ($media->getType() == 'video' && $media->getShortCode() != $profile->last_key) {
                    $videos[] = [
                        'url' => $media->getVideoStandardResolutionUrl(),
                        'caption' => $media->getCaption()
                    ];
                } else {
                    break;
                }
            }
            $profile->update([
                'last_key' => $medias[0]->getShortCode()
            ]);
        }
        return $videos;
    }
}
