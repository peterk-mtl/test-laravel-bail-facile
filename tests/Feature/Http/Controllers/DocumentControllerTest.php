<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Document;
use Carbon\Carbon;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function getJsonDocumentDataStructure()
    {
        return [
            'data' => [
                'id',
                'type',
                'type_slug',
                'format' => [
                  'name',
                  'slug',
                  'e_signable',
                  'postable',
                  'emailable',
                ],
                'created_at',
                'updated_at',
                'completed',
                'user' => [
                  'id',
                  'first_name',
                  'last_name',
                  'email',
                ],
                'template',
              ]
        ];
    }

    /**
     * Test Index with 10 results
     *
     * @return void
     */
    public function test_index_with_ten_results()
    {
        Document::factory()->createDocuments(10);

        $response = $this->get('/api/documents');

        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 10);
    }

    public function test_index_with_wrong_parameters()
    {
        Document::factory()->createDocuments(10, 1, 'my_slug', Carbon::now()->yesterday());

        $response = $this->get('/api/documents?slug=random_slug');
        $response->assertStatus(422)
            ->assertJsonPath('errors.0', 'The selected slug is invalid.');

        $response = $this->get('/api/documents?user_id=454546');
        $response->assertStatus(422)
            ->assertJsonPath('errors.0', 'The selected user id is invalid.');

        $response = $this->get('/api/documents?created_at=' . Carbon::now()->addYear(1)->toDateString());
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 0);

        $response = $this->get('/api/documents?updated_at=' . Carbon::now()->addYear(1)->toDateString());
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 0);
    }

    public function test_index_with_good_parameters()
    {
        Document::factory()->createDocuments(50, 1, 'my_slug', '', '', Carbon::now()->toDateString());

        $response = $this->get('/api/documents?user_id=1&slug=my_slug');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.type_slug', 'my_slug')
            ->assertJsonCount(10, 'data');
    }

    public function test_show_with_no_documents()
    {
        $response = $this->get('/api/documents/1');
        $response->assertStatus(404);
    }

    public function test_show()
    {
        Document::factory()->createDocuments(1);

        $response = $this->get('/api/documents/1');
        $response->assertStatus(200)
            ->assertJsonStructure($this->getJsonDocumentDataStructure());
    }

    public function test_update_non_existing_document()
    {
        $response = $this->putJson('api/documents/1');
        $response->assertStatus(404);

        $response = $this->patchJson('api/documents/1');
        $response->assertStatus(404);
    }

    public function test_update_already_e_signed_document()
    {
        Document::factory()->createDocuments(1);

        $document = Document::find(1);
        $document->locked = true;
        $document->save();

        $response = $this->putJson('api/documents/1');
        $response->assertStatus(400)
            ->assertJsonPath('errors', 'This document is not e-signable or already signed');
    }

    public function test_update_e_signable_document()
    {
        Document::factory()->createDocuments(1);

        $document = Document::find(1);
        $document->documentType->documentFormat->e_signable = true;
        $document->documentType->documentFormat->postable = false;
        $document->locked = false;
        $document->save();

        $response = $this->putJson('api/documents/1');
        $response->assertStatus(200);
    }

    public function test_store_with_wrong_parameters()
    {
        Document::factory()->createDocuments(1);
        $document = Document::find(1);

        $response = $this->postJson('api/documents', ['user_id' => 1000, 'slug' => 'random_slug']);
        $response->assertStatus(400)
            ->assertJsonPath('errors.0', 'The selected user id is invalid.')
            ->assertJsonPath('errors.1', 'The selected slug is invalid.');
    }

    public function test_store()
    {
        Document::factory()->createDocuments(1);
        $document = Document::find(1);

        $response = $this->postJson('api/documents', ['user_id' => $document->user->id, 'slug' => $document->documentType->slug]);
        $response->assertStatus(201)
            ->assertJsonStructure($this->getJsonDocumentDataStructure());
    }

    public function test_delete_non_existing_document()
    {
        $response = $this->deleteJson('api/documents/1000');
        $response->assertStatus(404);
    }

    public function test_delete()
    {
        Document::factory()->createDocuments(1);
        $response = $this->deleteJson('api/documents/1');
        $response->assertStatus(200)
            ->assertJsonStructure($this->getJsonDocumentDataStructure());
    }
}
