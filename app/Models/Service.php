<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * @package App\Models
 *
 * @property int $service
 * @property string $name
 * @property string $type
 * @property string $category
 * @property int $rate
 * @property int $min
 * @property int $max
 * @property bool $dripfeed
 * @property int $average_time
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

    /**
     * @return float|int
     */
    public function getRatePerOne()
    {
        return $this->rate / 1000;
    }

}
