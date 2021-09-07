<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Exceptions\ExternalApiException;
use App\Models\States\Order\Processing;
use App\Services\ExternalApi\Client;
use App\Services\ExternalApi\DTO\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Spatie\ModelStates\Exceptions\CouldNotPerformTransition;
use Throwable;

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

        $context = ['order' => $orderDto->toArray()];
        $this->log('info', 'Sending order to the external API', $context);

        try {
            $result = $client->sendOrder($orderDto);
        } catch (ExternalApiException $e) {
            $this->log('error', 'Order sending failed. Reason: ' . $e->getMessage(), $context);
            $this->retry();
            return;
        }

        $context = array_merge($context, ['result' => $result]);
        $this->log('info', 'Order send successfully. API respond with data (result)', $context);

        if ($externalId = Arr::get($result, 'order')) {
            // Set external id to check status with
            $order->external_id = $externalId;

            // Change order state to know that it's processing by the API
            try {
                $order->state->transitionTo(Processing::class);
            } catch (CouldNotPerformTransition $e) {
                $this->log(
                    'error',
                    'Failed to change order state to ' . Processing::$name . '. Reason: ' . $e->getMessage(),
                    $context
                );
                $this->retry();
                return;
            }

            // Save order
            try {
                $order->saveOrFail();
            } catch (Throwable $e) {
                $this->log(
                    'error',
                    'Failed to update the order model with external_id field. Reason: ' . $e->getMessage(),
                    $context
                );
                $this->retry();
                return;
            }
        }
    }


    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        $message = sprintf('%s: %s', __CLASS__, $message);
        Log::channel('external_api_log')->log($level, $message, $context);
    }


    /**
     * @return void
     */
    protected function retry(): void
    {
        // Retry after 5 minutes
        $this->release(60 * 5);
    }
}
