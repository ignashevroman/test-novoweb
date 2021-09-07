<?php

namespace App\Console\Commands;

use App\Exceptions\ExternalApiException;
use App\Models\Order;
use App\Models\States\Order\Completed;
use App\Models\States\Order\Processing;
use App\Services\ExternalApi\Client;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateOrdersStatuses extends Command
{
    public const LIMIT = 20;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external-api:update-orders-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets processing orders from database and checks them on the external API';

    /**
     * Execute the console command.
     *
     * @param Client $client
     * @return int
     */
    public function handle(Client $client): int
    {
        // Get processing orders from database
        $processing = Order::whereState('state', Processing::class)->oldest()->limit(self::LIMIT)->get();
        $this->info(sprintf('Found %d order(-s) in the %s status', $processing->count(), Processing::$name));

        if (!$processing->count()) {
            return 0;
        }

        // Find orders which completed in the API
        $completed = [];
        foreach ($processing as $order) {
            /** @var Order $order */
            try {
                $response = $client->getOrderStatus($order->external_id);
            } catch (ExternalApiException $e) {
                $this->error(sprintf('Failed to get order status for %d (%s). Reason: %s', $order->external_id, $order->id, $e->getMessage()));
                continue;
            }

            // TODO: change condition when more info about API will be known
            if (($status = $response['status']) && $status !== 'Pending') {
                $completed[] = $order->id;
            }
        }

        $this->info(sprintf('%d order(-s) was completed', count($completed)));
        if (!count($completed)) {
            return 0;
        }

        $this->info('Updating states in the database...');

        // Update these orders in the database
        try {
            DB::transaction(static function () use ($completed, $processing) {
                $processing
                    ->whereIn('id', $completed)
                    ->each(
                        static function (Order $order) {
                            $order->state->transitionTo(Completed::class);
                            $order->save();
                        }
                    );
            });
        } catch (Exception $e) {
            $this->error('Failed to update orders statuses. Reason: ' . $e->getMessage());
            return 1;
        }

        $this->info('Orders statuses updated successfully');
        return 0;
    }
}
