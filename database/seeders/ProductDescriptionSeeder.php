<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductDescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $spreadsheet = IOFactory::load(
            database_path('seeders/Black5Creatives Contents - Description.csv')
        );

        $rows = $spreadsheet->getActiveSheet()->toArray();
        // dd(array_slice($rows, 0, 10));

        foreach ($rows as $index => $row) {

            if (empty($row[0])) {
                continue;
            }

            // Skip header
            if ($row[0] === 'id') {
                continue;
            }

            $productId = (int) $row[0];
            $sort_description = trim($row[6]);
            $long_description = trim($row[7]);

            Product::where('id', $productId)->update([
                'sort_description' => $sort_description,
                'long_description' => $long_description
            ]);

            echo "Updated Product {$productId} -> Short: {$sort_description}, Long: {$long_description}\n";
        }
    }
}