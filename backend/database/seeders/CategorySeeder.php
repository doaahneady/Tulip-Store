<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name'=>'Electronics','slug'=>'electronics','image'=>'/images/category/2.6TV.jpg','description'=>'Devices, gadgets, and more.'],
            ['name'=>'Fashion','slug'=>'fashion','image'=>'/images/category/1.2women.jpg','description'=>'Trendy and classic apparel.'],
            ['name'=>'Books','slug'=>'books','image'=>'/images/category/6.2.jpg','description'=>'Books and literature.'],
            ['name'=>'Shoes','slug'=>'shoes','image'=>'/images/category/1.6shoes.jpg','description'=>'Footwear for every occasion.'],
            ['name'=>'Bags','slug'=>'bags','image'=>'/images/category/1.5bags.jpeg','description'=>'Handbags and backpacks.'],
            ['name'=>'Toys','slug'=>'toys','image'=>'/images/category/3.1education.jpg','description'=>'Fun and learning.'],
            ['name'=>'Sports','slug'=>'sports','image'=>'/images/category/4.1fitness.jpg','description'=>'Sport gear and wear.'],
            ['name'=>'Jewelry','slug'=>'jewelry','image'=>'/images/category/5.1jewelry.jpg','description'=>'Shine and elegance.'],
            ['name'=>'Care','slug'=>'care','image'=>'/images/category/7.2.jpg','description'=>'Personal care.'],
            ['name'=>'Kitchen','slug'=>'kitchen','image'=>'/images/category/8.1.jpg','description'=>'Home and kitchen.'],
        ];
        foreach ($categories as $c) {
            $cat = Category::updateOrCreate(['slug'=>$c['slug']], $c);
            // Add some products in each
            for ($i = 1; $i <= 8; $i++) {
                $img = $c['slug']==='electronics'
                    ? ['/images/category/2.1phone.jpeg','/images/category/2.2laptop.jpg','/images/category/2.3tap.jpg','/images/category/2.4smartWatch.jpg','/images/category/2.5earbuds.jpg','/images/category/2.6TV.jpg','/images/category/2.7cameras.jpg','/images/category/2.1phone.jpeg'][$i-1]
                    : ($c['slug']==='fashion'
                        ? ['/images/category/1.1men.jpg','/images/category/1.2women.jpg','/images/category/1.4kids.jpg','/images/category/1.5bags.jpeg','/images/category/1.6shoes.jpg','/images/category/1.7menShoes.jpg','/images/category/1.8BabyShoes.jpg','/images/category/1.3baby.jpeg'][$i-1]
                        : ($c['slug']==='books'
                            ? ['/images/category/6.2.jpg','/images/category/6.4bottles.jpg','/images/category/6.3stickers.jpg','/images/category/6.1bags.jpg','/images/category/6.2.jpg','/images/category/6.4bottles.jpg','/images/category/6.3stickers.jpg','/images/category/6.1bags.jpg'][$i-1]
                            : ($c['slug']==='shoes' ? ['/images/category/1.6shoes.jpg','/images/category/1.7menShoes.jpg','/images/category/1.8BabyShoes.jpg','/images/category/1.6shoes.jpg','/images/category/1.7menShoes.jpg','/images/category/1.8BabyShoes.jpg','/images/category/1.6shoes.jpg','/images/category/1.7menShoes.jpg'][$i-1]
                            : ($c['slug']==='bags' ? ['/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg','/images/category/1.5bags.jpeg'][$i-1]
                            : ($c['slug']==='toys' ? ['/images/category/3.1education.jpg','/images/category/3.2building.jpg','/images/category/3.3remot.jpg','/images/category/3.4doll.jpg','/images/category/3.1education.jpg','/images/category/3.2building.jpg','/images/category/3.3remot.jpg','/images/category/3.4doll.jpg'][$i-1]
                            : ($c['slug']==='sports' ? ['/images/category/4.1fitness.jpg','/images/category/4.2sportwear.jpg','/images/category/4.1fitness.jpg','/images/category/4.2sportwear.jpg','/images/category/4.1fitness.jpg','/images/category/4.2sportwear.jpg','/images/category/4.1fitness.jpg','/images/category/4.2sportwear.jpg'][$i-1]
                            : ($c['slug']==='jewelry' ? ['/images/category/5.1jewelry.jpg','/images/category/5.2watch.jpg','/images/category/5.4watch2.jpg','/images/category/5.3sunglass.jpg','/images/category/5.1jewelry.jpg','/images/category/5.2watch.jpg','/images/category/5.4watch2.jpg','/images/category/5.3sunglass.jpg'][$i-1]
                            : ($c['slug']==='care' ? ['/images/category/7.1.jpg','/images/category/7.2.jpg','/images/category/7.4.jpg','/images/category/7.8.jpg','/images/category/7.1.jpg','/images/category/7.2.jpg','/images/category/7.4.jpg','/images/category/7.8.jpg'][$i-1]
                            : ['/images/category/8.1.jpg','/images/category/8.2.jpg','/images/category/8.1.jpg','/images/category/8.2.jpg','/images/category/8.1.jpg','/images/category/8.2.jpg','/images/category/8.1.jpg','/images/category/8.2.jpg'][$i-1]
                            ))))))
                    );
                $product = Product::updateOrCreate([
                    'slug' => $c['slug'] . '-product-' . $i,
                ],[
                    'name' => $c['name'] . ' Product ' . $i,
                    'description' => $c['name'] . ' Description for product '.$i,
                    'price' => rand(10, 250) + ($i * 5),
                    'image' => $img,
                    'rate' => rand(10,50)/10,
                    'category_id' => $cat->id,
                    'sales' => rand(1,250),
                ]);
                // Dummy attributes by category
                if ($c['slug'] === 'electronics') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=> 'brand','value'=>['Samsung','Apple','HP','Sony'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'color','value'=>['Black','White','Silver','Gray'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'fashion') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'size','value'=>['M','L','XL','S'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'material','value'=>['Cotton','Wool','Polyester','Silk'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'books') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'author','value'=>'Author '.chr(64+$i)],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'year','value'=>strval(2000+$i)],[]);
                }
                if ($c['slug'] === 'shoes') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['sport','formal','sandal','boot'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'gender','value'=>['men','women','kids','unisex'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'bags') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['handbag','backpack','tote','crossbody'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'material','value'=>['leather','fabric','nylon','synthetic'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'toys') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'age','value'=>['3+','5+','8+','12+'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['education','building','remote','doll'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'sports') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['fitness','wear','accessory','gear'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'brand','value'=>['Nike','Adidas','Puma','Reebok'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'jewelry') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['ring','necklace','watch','sunglass'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'material','value'=>['gold','silver','steel','alloy'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'care') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['hair','nail','perfume','skin'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'brand','value'=>['BrandA','BrandB','BrandC','BrandD'][($i-1)%4]],[]);
                }
                if ($c['slug'] === 'kitchen') {
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'type','value'=>['bottle','utensil','appliance','container'][($i-1)%4]],[]);
                    ProductAttribute::updateOrCreate(['product_id'=>$product->id,'name'=>'material','value'=>['plastic','steel','glass','wood'][($i-1)%4]],[]);
                }
            }
        }
    }
}
