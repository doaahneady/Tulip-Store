<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class UpdateProductsWithFiltersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $colors = ['black', 'white', 'red', 'blue', 'green', 'pink', 'purple', 'orange'];
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $materials = ['Cotton', 'Polyester', 'Leather', 'Plastic', 'Wood', 'Metal', 'Paper'];
        $brands = ['Nike', 'Adidas', 'Zara', 'H&M', 'Apple', 'Samsung', 'Sony', 'Generic'];
        $conditions = ['new', 'used', 'refurbished'];
        $authors = ['J.K. Rowling', 'Stephen King', 'Agatha Christie', 'Dan Brown', 'Paulo Coelho'];
        $genres = ['Fiction', 'Non-Fiction', 'Mystery', 'Romance', 'Science Fiction', 'Biography'];

        foreach ($categories as $category) {
            $products = Product::where('category_id', $category->id)->get();

            foreach ($products as $product) {
                $data = [
                    'stock' => rand(0, 100),
                    'condition' => $conditions[array_rand($conditions)],
                ];

                // Category-specific filters
                $slug = strtolower($category->slug);

                // Clothing categories
                if (str_contains($slug, 'cloth') || str_contains($slug, 'fashion') || str_contains($slug, 'wear')) {
                    $data['color'] = $colors[array_rand($colors)];
                    $data['size'] = $sizes[array_rand($sizes)];
                    $data['material'] = $materials[array_rand(['Cotton', 'Polyester', 'Leather'])];
                    $data['brand'] = $brands[array_rand(['Zara', 'H&M', 'Nike', 'Adidas'])];
                }

                // Shoes
                elseif (str_contains($slug, 'shoe') || str_contains($slug, 'footwear')) {
                    $data['color'] = $colors[array_rand($colors)];
                    $data['size'] = rand(36, 46); // Shoe sizes
                    $data['material'] = $materials[array_rand(['Leather', 'Synthetic'])];
                    $data['brand'] = $brands[array_rand(['Nike', 'Adidas'])];
                }

                // Books
                elseif (str_contains($slug, 'book') || str_contains($slug, 'reading')) {
                    $data['author'] = $authors[array_rand($authors)];
                    $data['genre'] = $genres[array_rand($genres)];
                    $data['pages'] = rand(100, 800);
                    $data['brand'] = 'Penguin Books';
                }

                // Toys
                elseif (str_contains($slug, 'toy') || str_contains($slug, 'kids') || str_contains($slug, 'children')) {
                    $data['color'] = $colors[array_rand($colors)];
                    $data['age_range'] = rand(1, 12); // Age 1-12
                    $data['material'] = $materials[array_rand(['Plastic', 'Wood'])];
                    $data['brand'] = $brands[array_rand(['LEGO', 'Mattel', 'Hasbro'])];
                }

                // Electronics
                elseif (str_contains($slug, 'electron') || str_contains($slug, 'tech') || str_contains($slug, 'gadget')) {
                    $data['color'] = $colors[array_rand(['black', 'white', 'blue'])];
                    $data['brand'] = $brands[array_rand(['Apple', 'Samsung', 'Sony'])];
                    $data['material'] = 'Metal';
                }

                // Accessories
                elseif (str_contains($slug, 'access') || str_contains($slug, 'jewelry')) {
                    $data['color'] = $colors[array_rand($colors)];
                    $data['material'] = $materials[array_rand(['Metal', 'Leather', 'Plastic'])];
                    $data['brand'] = $brands[array_rand($brands)];
                }

                // Default for other categories
                else {
                    $data['color'] = $colors[array_rand($colors)];
                    $data['brand'] = $brands[array_rand($brands)];
                }

                $product->update($data);
            }
        }

        $this->command->info('Products updated with filter data successfully!');
    }
}
