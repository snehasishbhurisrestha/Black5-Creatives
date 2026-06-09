<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductPrimaryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $spreadsheet = IOFactory::load(
            database_path('seeders/products-with-primary-category.xlsx')
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
            $categoryName = trim($row[3]);

            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                echo "Category not found: {$categoryName}\n";
                continue;
            }

            Product::where('id', $productId)->update([
                'primary_category_id' => $category->id
            ]);

            echo "Updated Product {$productId} -> Category {$category->id}\n";
        }
    }
}