<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\PriceObservation;
use App\Models\Retailer;
use Illuminate\Console\Command;

class ImportPricesCsv extends Command
{
    protected $signature = 'prices:import {file}';

    protected $description = 'Import price observations from a CSV file';

    public function handle(): int
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        $imported = 0;
        $lineNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            if (count($row) < 4) {
                $this->error("Line {$lineNumber}: expected 4 columns, got " . count($row));
                fclose($handle);
                return self::FAILURE;
            }

            [$ingredientSlug, $retailerSlug, $pricePence, $observedOn] = $row;

            $ingredient = Ingredient::where('slug', trim($ingredientSlug))->first();
            if (!$ingredient) {
                $this->error("Line {$lineNumber}: unknown ingredient slug '{$ingredientSlug}'");
                fclose($handle);
                return self::FAILURE;
            }

            $retailer = Retailer::where('slug', trim($retailerSlug))->first();
            if (!$retailer) {
                $this->error("Line {$lineNumber}: unknown retailer slug '{$retailerSlug}'");
                fclose($handle);
                return self::FAILURE;
            }

            if (!is_numeric($pricePence) || (int) $pricePence < 0) {
                $this->error("Line {$lineNumber}: invalid price_pence '{$pricePence}'");
                fclose($handle);
                return self::FAILURE;
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($observedOn))) {
                $this->error("Line {$lineNumber}: invalid observed_on '{$observedOn}' (expected YYYY-MM-DD)");
                fclose($handle);
                return self::FAILURE;
            }

            PriceObservation::updateOrCreate(
                [
                    'ingredient_id' => $ingredient->id,
                    'retailer_id' => $retailer->id,
                    'observed_on' => trim($observedOn),
                ],
                [
                    'price_pence' => (int) $pricePence,
                    'source' => 'csv',
                ]
            );

            $imported++;
        }

        fclose($handle);

        $this->info("Imported {$imported} price observations.");

        return self::SUCCESS;
    }
}
