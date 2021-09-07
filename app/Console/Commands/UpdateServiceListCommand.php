<?php

namespace App\Console\Commands;

use App\Exceptions\ExternalApiException;
use App\Models\Service;
use App\Services\ExternalApi\Client;
use Illuminate\Console\Command;

class UpdateServiceListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'external-api:update-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command gets service list from the external API and saves them into DB';

    /**
     * Execute the console command.
     *
     * @param Client $client
     * @return int
     */
    public function handle(Client $client): int
    {
        // Get services from external API
        $services = $this->loadServices($client);
        if (is_int($services)) {
            return $services;
        }

        // Save them
        $this->saveServicesIntoDatabase($services);
        return 0;
    }

    /**
     * @param Client $client
     * @return int|array
     */
    protected function loadServices(Client $client)
    {
        $this->info('Getting services from external API...');
        try {
            $services = $client->getServices();
        } catch (ExternalApiException $e) {
            $this->error($e->getMessage());
            return 1;
        }
        $this->info(sprintf("%d services received from external API", count($services)));

        return $services;
    }

    /**
     * @param array $services
     */
    protected function saveServicesIntoDatabase(array $services): void
    {
        $primary = (new Service())->getKeyName();
        Service::query()
            ->upsert(
                $services,
                [$primary],
                array_diff(array_keys(array_shift($services)), [$primary])
            );

        $this->info("Saving into database completed");
    }
}
