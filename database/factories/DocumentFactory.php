<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_at' => $this->faker->dateTimeBetween('-20 days', '-10 days'),
            'updated_at' => $this->faker->dateTimeBetween('-10 days', now()),
            'locked' => mt_rand(0, 1),
        ];
    }

    public function createDocuments(int $nbElementsToCreate, int $nbUsers = 1, string $slug = '', string $createAt = '', string $updatedAt = '')
    {
        $state = [];
        $users = User::factory()->count($nbUsers)->create();

        if ('' !== $slug) {
            $documentType = DocumentType::factory()->state(['slug' => $slug])->create();
        } else {
            $documentType = DocumentType::factory()->create();
        }

        if ('' !== $createAt) {
            $state['created_at'] = $createAt;
        }

        if ('' !== $updatedAt) {
            $state['updated_at'] = $updatedAt;
        }

        foreach ($users as $user) {
            Document::factory()
                ->count($nbElementsToCreate)
                ->for($user)
                ->for($documentType)
                ->state($state)
                ->create();
        }
    }
}
