<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @property int $id
 * @property int $channel_id
 * @property int $insta_profile_id
 * @property string $last_key
 * @property Channel $channel
 * @property InstaProfile $profile
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ChannelProfile extends Model
{
    use HasFactory;

    protected $table = 'channel_profile';

    protected $fillable = [
        'channel_id',
        'insta_profile_id',
        'last_key'
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(InstaProfile::class, 'insta_profile_id', 'id');
    }
}
