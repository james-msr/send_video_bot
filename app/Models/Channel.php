<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @property int $id
 * @property string $channel_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_name',
    ];

    public function profiles()
    {
        return $this->belongsToMany(InstaProfile::class, 'channel_profile', 'channel_id', 'insta_profile_id')->withPivot('last_key');
    }
}
