<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 * @package App\Models
 *
 * @method static int upsert(array $values, array|string $uniqueBy, array|null $update = null)
 * @method static Profile|Builder firstOrCreate(array $attributes = [], array $values = [])
 * @method static Profile|Builder updateOrCreate(array $attributes, array $values = [])
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
    ];
}
