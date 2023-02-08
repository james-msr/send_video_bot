<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @property int $id
 * @property string $profile_name
 * @property string $last_key
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class InstaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_name',
        'last_key'
    ];
}
