<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\CarMake;
use App\Models\CarModel;

class ImportCarData extends Command
{
    protected $signature = 'cars:import';
    protected $description = 'Import car makes and models from CarQuery API';

    public function handle(): void
    {
        $this->info('Fetching car makes...');

        // Fetch all makes
        $response = Http::get('https://www.carqueryapi.com/api/0.3/?cmd=getMakes');
        $data = json_decode($response->body(), true);

        if (empty($data['Makes'])) {
            $this->error('No makes returned.');
            return;
        }

        foreach ($data['Makes'] as $makeData) {
            $makeName = $makeData['make_display'] ?? $makeData['make_name'];
            $make = CarMake::updateOrCreate(
                ['name' => $makeName],
                ['slug' => Str::slug($makeName)]
            );

            $this->line("Saved make: {$makeName}");

            // Fetch models for this make
            $modelsResponse = Http::get("https://www.carqueryapi.com/api/0.3/?cmd=getModels&make=" . $makeData['make_id']);
            $modelsData = json_decode($modelsResponse->body(), true);

            if (!empty($modelsData['Models'])) {
                foreach ($modelsData['Models'] as $modelData) {
                    $modelName = $modelData['model_name'];
                    CarModel::updateOrCreate(
                        ['name' => $modelName, 'car_make_id' => $make->id],
                        [
                            'slug' => Str::slug($modelName),
                            'group' => $modelData['model_trim'] ?? null
                        ]
                    );
                }

                $this->line("  → Imported " . count($modelsData['Models']) . " models for {$makeName}");
            }

            sleep(1);
        }

        $this->info('✅ Import completed successfully!');
    }
}
