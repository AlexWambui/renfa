<?php
// app/Console/Commands/ImportProducts.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductCategory;

class ImportProducts extends Command
{
    protected $signature = 'import:products 
                            {file? : Path to CSV file (default: storage/app/public/products.csv)}
                            {--dry-run : Preview import without saving}
                            {--create-categories : Auto-create missing categories}
                            {--skip-duplicates : Skip duplicate products}
                            {--update-existing : Update existing products instead of skipping}';
    
    protected $description = 'Import products from CSV file with category handling';

    private array $stats = [
        'total' => 0,
        'imported' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => 0,
        'categories_created' => 0,
    ];

    public function handle(): int
    {
        $this->info('🚀 Starting product import...');
        $this->newLine();

        // Get and validate file
        $file = $this->getFile();
        if (!$file) {
            return 1;
        }

        // Parse CSV
        $data = $this->parseCSV($file);
        if (!$data) {
            return 1;
        }

        [$header, $rows] = $data;
        $this->stats['total'] = count($rows);

        // Validate headers
        if (!$this->validateHeaders($header)) {
            return 1;
        }

        // Prepare categories
        $categories = $this->prepareCategories($rows, $header);
        
        // Import products
        $this->importProducts($rows, $header, $categories);

        // Show results
        $this->showResults();

        return 0;
    }

    private function getFile(): ?string
    {
        $file = $this->argument('file') ?? storage_path('app/public/products.csv');
        
        if (!file_exists($file)) {
            $this->error("❌ File not found: $file");
            
            // Try alternative locations
            $alternatives = [
                base_path('products.csv'),
                base_path('database/seeders/products.csv'),
                public_path('products.csv'),
            ];
            
            foreach ($alternatives as $alt) {
                if (file_exists($alt)) {
                    $file = $alt;
                    $this->line("  📂 Found file at: $file");
                    break;
                }
            }
            
            if (!file_exists($file)) {
                $this->error("❌ Could not find CSV file");
                $this->line("  Place your CSV file at: storage/app/public/products.csv");
                return null;
            }
        }

        $this->line("  📂 File: $file");
        return $file;
    }

    private function parseCSV(string $file): ?array
    {
        try {
            $rows = array_map('str_getcsv', file($file));
            
            if (empty($rows)) {
                $this->error('❌ CSV file is empty');
                return null;
            }

            $header = array_shift($rows);
            
            // Clean header
            $header = array_map('trim', $header);
            
            // Remove empty rows
            $rows = array_filter($rows, function($row) {
                return !empty(array_filter($row));
            });

            $this->line("  📊 Found " . count($rows) . " products to process");
            $this->newLine();

            return [$header, $rows];

        } catch (\Exception $e) {
            $this->error('❌ Failed to parse CSV: ' . $e->getMessage());
            return null;
        }
    }

    private function validateHeaders(array $header): bool
    {
        $required = ['name', 'category', 'price'];
        $missing = [];

        foreach ($required as $field) {
            if (!in_array($field, $header)) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            $this->error('❌ Missing required columns: ' . implode(', ', $missing));
            $this->line('  Required: ' . implode(', ', $required));
            $this->line('  Found: ' . implode(', ', $header));
            return false;
        }

        return true;
    }

    private function prepareCategories(array $rows, array $header): array
    {
        $this->info('📋 Preparing categories...');
        
        $headerMap = array_flip($header);
        $categoryNames = [];

        // Extract unique categories
        foreach ($rows as $row) {
            $categoryName = trim($row[$headerMap['category']] ?? '');
            if (!empty($categoryName)) {
                $categoryNames[$categoryName] = true;
            }
        }

        $categoryNames = array_keys($categoryNames);
        $this->line("  Found " . count($categoryNames) . " unique categories");

        // Get existing categories - KEYED BY NAME
        $categories = ProductCategory::all()->mapWithKeys(function($item) {
            return [trim($item->name) => $item->id];
        })->toArray();

        // Debug
        $this->line("  Categories array keys: " . json_encode(array_keys($categories)));
        $this->line("  CSV categories: " . json_encode($categoryNames));

        return $categories;
    }

    private function importProducts(array $rows, array $header, array $categories): void
    {
        $this->info('📥 Importing products...');
        $headerMap = array_flip($header);

        $bar = $this->output->createProgressBar($this->stats['total']);
        $bar->start();

        foreach ($rows as $row) {
            $this->importRow($row, $headerMap, $categories);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
    }

    private function importRow(array $row, array $headerMap, array $categories): void
    {
        try {
            // Extract data
            $name = trim($row[$headerMap['name']] ?? '');
            $categoryName = trim($row[$headerMap['category']] ?? '');
            $description = trim($row[$headerMap['description']] ?? '');
            $price = (float) ($row[$headerMap['price']] ?? 0);
            
            // Handle boolean fields
            $isPopular = isset($headerMap['is_popular']) 
                ? strtolower(trim($row[$headerMap['is_popular']] ?? 'No')) === 'yes'
                : false;
                
            $isActive = isset($headerMap['is_active'])
                ? strtolower(trim($row[$headerMap['is_active']] ?? 'Yes')) === 'yes'
                : true;

            // Validate required fields
            if (empty($name)) {
                $this->stats['skipped']++;
                return;
            }

            if (empty($price) || $price <= 0) {
                $this->stats['skipped']++;
                return;
            }

            // Get category ID
            $categoryId = $categories[$categoryName] ?? null;
            if (!$categoryId) {
                $this->stats['skipped']++;
                return;
            }

            // Create new product
            Product::create([
                'name' => $name,
                'description' => $description ?: null,
                'price' => $price,
                'product_category_id' => $categoryId,
                'is_active' => $isActive,
                'is_new' => false,
                'is_featured' => false,
                'current_stock' => 0,
                'track_inventory' => false,
                'low_stock_threshold' => 5,
            ]);

            $this->stats['imported']++;

        } catch (\Exception $e) {
            $this->stats['errors']++;
            Log::error('Product import error: ' . $e->getMessage(), [
                'row' => $row,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function showResults(): void
    {
        $this->newLine();
        $this->info('📊 Import Results:');
        $this->newLine();

        $rows = [
            ['↕️ Total rows', $this->stats['total']],
            ['✅ Imported', $this->stats['imported']],
            ['⚒️ Updated', $this->stats['updated']],
            ['⚠️ Skipped', $this->stats['skipped']],
            ['❌ Errors', $this->stats['errors']],
            ['✅ Categories created', $this->stats['categories_created']],
        ];

        $this->table(['Metric', 'Count'], $rows);

        if ($this->stats['errors'] > 0) {
            $this->warn('⚠️ Check the log file for errors: storage/logs/laravel.log');
        }

        $this->newLine();
        $this->info('✅ Import complete!');
    }
}