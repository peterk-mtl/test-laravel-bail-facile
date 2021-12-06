<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentFormat;

class DocumentFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creates contract format
        DocumentFormat::firstOrCreate(
            [
                'id' => 1,
                'name' => 'Contract',
                'e_signable' => true,
                'postable'=> false,
                'emailable'=> true,
            ]
        );

        //creates letter format
        DocumentFormat::firstOrCreate(
            [
                'id' => 2,
                'name' => 'Letter',
                'e_signable' => false,
                'postable'=> true,
                'emailable'=> true,
            ]
        );
    }
}
