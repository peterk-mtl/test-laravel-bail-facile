<?php

namespace Database\Factories;

use App\Models\DocumentFormat;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $documentFormat = DocumentFormat::factory()->make();

        return [
            'name' => $this->faker->name(),
            'document_format_id' => DocumentFormat::factory()->create(),
        ];
    }
}
