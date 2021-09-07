<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App\Models
 *
 * @property int $id
 * @property string $full_name
 * @property string $username
 * @property string $profile_pic_url
 * @property string $profile_pic_url_hd
 * @property string $url
 */
class Profile extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'full_name',
        'username',
        'profile_pic_url',
        'profile_pic_url_hd',
        'url',
    ];
}
