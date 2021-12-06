<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Document;
use App\Models\DocumentType;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = DocumentType::all();

        if (User::count() < $documentTypes->count()) {
            $users = User::factory()
                ->count($documentTypes->count())
                ->create();
        } else {
            $users = User::limit($documentTypes->count())->get();
        }

        foreach ($users as $index => $user) {
            foreach ($documentTypes as $documentType) {
                Document::factory()
                    ->create([
                        'user_id' => $user->id,
                        'document_type_id' => $documentType->id,
                    ]);
            }
        }
    }
}
