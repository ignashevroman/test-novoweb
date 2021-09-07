<?php


namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait UsesUuid
 * @package App\Models\Traits
 *
 * @mixin Model
 */
trait UsesUuid
{
    protected static function bootUsesUuid(): void
    {
        static::creating(
            function (Model $model) {
                if (!$model->getKey()) {
                    $model->{$model->getKeyName()} = (string)Str::uuid();
                }
            }
        );
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
