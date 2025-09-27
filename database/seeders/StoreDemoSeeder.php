<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class StoreDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Categorias
        $cat1 = Category::firstOrCreate(['slug'=>'raquetes'], ['name'=>'Raquetes']);
        $cat2 = Category::firstOrCreate(['slug'=>'bolas'], ['name'=>'Bolas']);
        $cat3 = Category::firstOrCreate(['slug'=>'acessorios'], ['name'=>'Acessórios']);

        // Produtos
        $p1 = Product::firstOrCreate(
            ['slug' => 'raquete-pro-demo'],
            [
                'name' => 'Raquete Pro Demo',
                'description' => "Raquete leve e resistente para jogadores intermediários.\nTamanho de cabeça 100 in², equilíbrio 320 mm.",
                'price' => 1299.90,
                'is_digital' => false,
                'stock' => 12,
                'is_active' => true,
            ]
        );
        $p1->categories()->syncWithoutDetaching([$cat1->id]);

        $p2 = Product::firstOrCreate(
            ['slug' => 'tubo-bolas-pressurizadas'],
            [
                'name' => 'Tubo de Bolas (Pressurizadas)',
                'description' => "Tubo com 3 bolas pressurizadas. Alto desempenho e durabilidade.",
                'price' => 59.90,
                'is_digital' => false,
                'stock' => 100,
                'is_active' => true,
            ]
        );
        $p2->categories()->syncWithoutDetaching([$cat2->id]);

        $p3 = Product::firstOrCreate(
            ['slug' => 'overgrip-premium-afiliado'],
            [
                'name' => 'Overgrip Premium (Afiliado)',
                'description' => "Pacote com 3 overgrips antiderrapantes.",
                'price' => 39.90,
                'is_digital' => false,
                'stock' => 0,
                'affiliate_url' => 'https://exemplo-parceiro.com/produto/overgrip-premium',
                'is_active' => true,
            ]
        );
        $p3->categories()->syncWithoutDetaching([$cat3->id]);

        // Observação: para imagens, suba pelo Admin > Produtos (Create/Edit).
    }
}
