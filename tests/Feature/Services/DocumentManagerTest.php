<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Services\DocumentManager;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DocumentManagerTest extends TestCase
{
    use RefreshDatabase;

    protected $documentManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->documentManager = $this->app->make(DocumentManager::class);
    }


    /**
     * @dataProvider provider
     */
    public function test_getPaginatedIndex(int $nbElementsToCreate, int $expectResultsNumber, array $parameters = [], int $nbUsers = 1, string $slug = '', string $createAt = '', string $updatedAt = '')
    {
        Document::factory()->createDocuments($nbElementsToCreate, $nbUsers, $slug, $createAt, $updatedAt);

        $results = $this->documentManager->getPaginatedIndex($parameters, $nbElementsToCreate)->toArray();
        $this->assertEquals($expectResultsNumber, $results['total']);
    }

    public function provider()
    {
        return [
            'With no documents records' => [5, 5],
            'With less documents records than requested' => [100, 100],
            'Filtered by non existing user id' => [1, 0, ['user_id' => 1000], 5],
            'Filtered by existing user id' => [10, 10, ['user_id' => 1], 5],
            'Filtered by non existing slug' => [1, 0, ['slug' => 'my_slug'], 5],
            '' => [5, 25, ['slug' => 'my_slug'], 5, 'my_slug'],
            'Filtered by "created at" to soon' => [1, 0, ['created_at' => Carbon::now()->toDateString()], 1, '', Carbon::yesterday()->toDateString()],
            'Filtered by "created at"' => [1, 1, ['created_at' => Carbon::yesterday()->toDateString()], 1, '', Carbon::now()->toDateString()],
            'Filtered by "updated at" to soon' => [1, 0, ['updated_at' => Carbon::now()->toDateString()], 1, '', '', Carbon::yesterday()->toDateString()],
            'Filtered by "updated at"' => [1, 1, ['updated_at' => Carbon::yesterday()->toDateString()], 1, '', '', Carbon::now()->toDateString()],
            'Filtered by multiple filters but wrong' => [
                1, 0, [
                    'user_id' => 1,
                    'slug' => 'my_slug',
                    'created_at' => Carbon::now()->toDateString(),
                    'updated_at' => Carbon::now()->toDateString(),
                   ], 1, 'my_slug', Carbon::yesterday()->toDateString(), Carbon::yesterday()->toDateString()
            ],
            'Filtered by multiple filters' => [
                10, 10, [
                    'user_id' => 1,
                    'slug' => 'my_slug',
                    'created_at' => Carbon::yesterday()->toDateString(),
                    'updated_at' => Carbon::yesterday()->toDateString(),
                   ], 1, 'my_slug', Carbon::now()->toDateString(), Carbon::now()->toDateString()
            ]
        ];
    }
}
