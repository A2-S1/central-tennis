<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\Category;
use App\Models\User;

class ClassifiedsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return; // sem usuário para atribuir
        }

        $catRaquetes = Category::firstOrCreate(['slug'=>'raquetes'], ['name'=>'Raquetes']);
        $catAcess = Category::firstOrCreate(['slug'=>'acessorios'], ['name'=>'Acessórios']);

        // Helper para baixar imagem demo
        $saveImg = function(string $url): string {
            $data = @file_get_contents($url);
            if ($data === false) {
                // fallback: cria imagem vazia
                $path = 'classifieds/'.Str::random(12).'.jpg';
                Storage::disk('public')->put($path, '');
                return $path;
            }
            $path = 'classifieds/'.Str::random(12).'.jpg';
            Storage::disk('public')->put($path, $data);
            return $path;
        };

        // Anúncio pendente
        $l1 = Listing::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Raquete Semi-nova',
        ], [
            'description' => 'Raquete 300g, encordoamento recente, poucas marcas de uso.',
            'price' => 850.00,
            'condition' => 'seminovo',
            'status' => 'pending',
        ]);
        $l1->categories()->syncWithoutDetaching([$catRaquetes->id]);
        if ($l1->images()->count() === 0) {
            $img = $saveImg('https://images.unsplash.com/photo-1541890925-4d8c5f05f44b?q=80&w=600&auto=format&fit=crop');
            $l1->images()->create(['path'=>$img,'is_primary'=>true]);
        }

        // Anúncio aprovado 1
        $l2 = Listing::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Bolsa para Raquetes',
        ], [
            'description' => 'Bolsa para 6 raquetes, excelente estado.',
            'price' => 299.90,
            'condition' => 'usado',
            'status' => 'approved',
        ]);
        $l2->categories()->syncWithoutDetaching([$catAcess->id]);
        if ($l2->images()->count() === 0) {
            $img = $saveImg('https://images.unsplash.com/photo-1599050762680-2b4706a3d8a0?q=80&w=600&auto=format&fit=crop');
            $l2->images()->create(['path'=>$img,'is_primary'=>true]);
        }

        // Anúncio aprovado 2
        $l3 = Listing::firstOrCreate([
            'user_id' => $user->id,
            'title' => 'Overgrip pacote com 12',
        ], [
            'description' => 'Pacote lacrado, várias cores.',
            'price' => 89.90,
            'condition' => 'novo',
            'status' => 'approved',
        ]);
        $l3->categories()->syncWithoutDetaching([$catAcess->id]);
        if ($l3->images()->count() === 0) {
            $img = $saveImg('https://images.unsplash.com/photo-1542482833-8ebcecd0b0e1?q=80&w=600&auto=format&fit=crop');
            $l3->images()->create(['path'=>$img,'is_primary'=>true]);
        }
    }
}
