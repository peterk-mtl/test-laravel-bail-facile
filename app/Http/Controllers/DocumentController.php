<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexDocumentRequest;
use App\Http\Requests\StoreDocumentRequest;
use Symfony\Component\HttpFoundation\Request;
use App\Models\Document;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Models\DocumentType;
use App\Services\DocumentManager;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/documents",
     *      operationId="getDocumentsLists",
     *      tags={"Documents"},
     *      summary="Get a paginated list of documents",
     *      description="Returns list of paginated documents",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User owning the document",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="slug",
     *          description="Filter by document type slug",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="created_at",
     *          description="Filter document with date > created_at",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="update_at",
     *          description="Filter document with date > updated_at",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="date"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong arguments",
     *      ),
     * ),
     */
    public function index(IndexDocumentRequest $indexDocumentRequest, DocumentManager $documentManager): DocumentCollection
    {
        $paginatedResults = $documentManager->getPaginatedIndex($indexDocumentRequest->all(), 10);

        return new DocumentCollection($paginatedResults);
    }

    /**
     * @OA\Post(
     *      path="/documents",
     *      operationId="postDocument",
     *      tags={"Documents"},
     *      summary="Create a document",
     *      description="Creates a document",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"user_id","slug"},
     *              @OA\Property(property="user_id", type="integer", format="integer"),
     *              @OA\Property(property="slug", type="string", format="string"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Wrong parameters",
     *      ),
     * ),
     */
    public function store(StoreDocumentRequest $storeDocumentRequest): DocumentResource
    {
        $documentType = DocumentType::where('slug', $storeDocumentRequest->slug)->first();

        $document = new Document();
        $document->user_id = $storeDocumentRequest->get('user_id');
        $document->document_type_id = $documentType->id;

        $document->save();

        return new DocumentResource($document);
    }

    /**
     * @OA\Get(
     *      path="/documents/{id}",
     *      operationId="getDocument",
     *      tags={"Documents"},
     *      summary="Get a document",
     *      description="Returns a document",
     *      @OA\Parameter(
     *          name="id",
     *          description="Document id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User does not exist",
     *      ),
     * ),
     */
    public function show(Document $document): DocumentResource
    {
        return new DocumentResource($document);
    }

    /**
     * @OA\Put(
     *      path="/documents/{id}",
     *      operationId="updateDocument",
     *      tags={"Documents"},
     *      summary="E-sign a document",
     *      description="E-sign a document",
     *      @OA\Parameter(
     *          name="id",
     *          description="Document id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Document is not e-sinable or already signed",
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Document does not exist",
     *     ),
     * ),
     */
    public function update(Request $request, int $documentId)
    {
        $documentExists = Document::where('id', $documentId)->exists();

        if (!$documentExists) {
            return response()->json(['errors' => 'Document not Found!'], 404);
        }

        $document = Document::where('id', $documentId)
            ->isUpdatable()
            ->first();

        if (!$document) {
            return response()->json(['errors' => 'This document is not e-signable or already signed'], 400);
        }

        $document->locked = true;
        $document->save();

        return new DocumentResource($document);
    }

    /**
     * @OA\Delete(
     *      path="/documents/{id}",
     *      operationId="deleteDocument",
     *      tags={"Documents"},
     *      summary="Delete existing document",
     *      description="Deletes a record and returns record",
     *      @OA\Parameter(
     *          name="id",
     *          description="Document id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * ),
     */
    public function destroy(Document $document): DocumentResource
    {
        $document->delete();

        return new DocumentResource($document);
    }
}
