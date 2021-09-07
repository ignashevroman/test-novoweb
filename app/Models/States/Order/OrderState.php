<?php


namespace App\Models\States\Order;


use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    /**
     * @return StateConfig
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Fresh::class)
            ->allowTransition(Fresh::class, Processing::class)
            ->allowTransition(Processing::class, Completed::class);
    }
}
