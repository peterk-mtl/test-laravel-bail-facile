<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DocumentFormatSeeder;
use Database\Seeders\DocumentTypeSeeder;
use Database\Seeders\DocumentSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DocumentFormatSeeder::class,
            DocumentTypeSeeder::class,
            DocumentSeeder::class
        ]);
    }
}
