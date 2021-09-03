<?php

namespace App\Console\Commands;

use App\Services\ExternalApi\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
    protected $description = 'Command gets service list from the external api and saves it into storage';

    /**
     * Execute the console command.
     *
     * @param Client $client
     * @return int
     */
    public function handle(Client $client): int
    {
        // Get services from external API
        $this->info('Getting services from external API...');
        $services = $client->getServices();
        $this->info(sprintf("%d services received from external API", count($services)));

        // Save into the file
        Storage::put(config('services.external_api.services_path'), json_encode($services, JSON_THROW_ON_ERROR));
        $this->info("Saving into a file completed");

        return 0;
    }
}
