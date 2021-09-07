<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Exceptions\ExternalApiException;
use App\Services\ExternalApi\Client;
use App\Services\ExternalApi\DTO\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendCreatedOrderToExternalApi implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    public $queue = 'external-api';

    /**
     * @var int
     */
    public $tries = 5;

    /**
     * Handle the event.
     *
     * @param OrderCreated $event
     * @return void
     */
    public function handle(OrderCreated $event): void
    {
        /** @var Client $client */
        $client = app(Client::class);

        $order = $event->order;
        $order->fresh(['profile']);

        $orderDto = new Order(
            $order->profile->url,
            $order->quantity,
            $order->service_id,
            $order->id
        );

        $context = [
            'order' => $orderDto->toArray()
        ];

        Log::info('Send order to external api', $context);
        try {
            $result = $client->sendOrder($orderDto);
            Log::info('External api respond with', array_merge($context, ['result' => $result]));
        } catch (ExternalApiException $e) {
            Log::error('Order sending failed. Reason: ' . $e->getMessage(), $context);
            // Retry after 5 minutes
            $this->release(60 * 5);
        }
    }
}
