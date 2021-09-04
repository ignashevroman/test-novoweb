<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * @package App\Models
 *
 * @method static int upsert(array $values, array|string $uniqueBy, array|null $update = null)
 */
class Service extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'service';

    /**
     * @var array
     */
    protected $fillable = [
        'service',
        'name',
        'type',
        'category',
        'rate',
        'min',
        'max',
        'dripfeed',
        'average_time',
    ];
}
