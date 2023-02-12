<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @package App\Models
 * @property int $id
 * @property string $profile_name
 * @property string $last_key
 * @property Collection $channels
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class InstaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_name',
    ];

    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'channel_profile', 'insta_profile_id', 'channel_id')->withPivot('last_key');
    }
}
