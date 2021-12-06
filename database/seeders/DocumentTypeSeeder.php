<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;
use App\Models\DocumentFormat;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contractFormat = DocumentFormat::find(1)->id;
        $letterFormat = DocumentFormat::find(2)->id;

        $documentTypes = [
            [
                'name' => 'Rental agreement',
                'document_format_id' => $contractFormat,
            ],
            [
                'name' => 'Rent guarantee agreement',
                'document_format_id' => $contractFormat,
            ],
            [
                'name' => 'Sub-letting agreement',
                'document_format_id' => $contractFormat,
            ],
            [
                'name' => 'Rental agreement amendment',
                'document_format_id' => $contractFormat,
            ],
            [
                'name' => 'Rent receipt',
                'document_format_id' => $letterFormat,
            ],
            [
                'name' => 'Rent invoice',
                'document_format_id' => $letterFormat,
            ],
            [
                'name' => 'Late payment letter',
                'document_format_id' => $letterFormat,
            ]
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::firstOrCreate($documentType);
        }
    }
}
