<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $category = new Categories;
        $category->name = 'Livre';
        $category->description = 'livres, revues, mode d\'emplois, ouvrages techniques, documentations';
        $category->save();

        $category = new Categories;
        $category->name = 'Audio';
        $category->description = 'Microphones, Dispositifs d\'aquisiton, accessoires audio, batteries';
        $category->save();

        $category = new Categories;
        $category->name = 'Video';
        $category->description = 'Caméras, appareils photo, accessoires vidéo, batteries';
        $category->save();
    }
}
