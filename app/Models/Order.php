<?php

namespace App\Models;

use App\Events\OrderCreated;
use App\Models\States\Order\OrderState;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

/**
 * Class Order
 * @package App\Models
 *
 * @property string $id
 * @property int $profile_id
 * @property int $service_id
 * @property int $quantity_of_completed
 * @property int $quantity
 * @property double $charge
 * @property int $external_id
 * @property OrderState $state
 *
 * @property Profile $profile
 */
class Order extends Model
{
    use HasFactory;
    use UsesUuid;
    use HasStates;

    /**
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'service_id',
        'quantity_of_completed',
        'quantity',
        'charge',
    ];

    protected $casts = [
        'state' => OrderState::class,
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => OrderCreated::class,
    ];

    /**
     * @return BelongsTo
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'service');
    }
}
