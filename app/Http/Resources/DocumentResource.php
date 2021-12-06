<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DocumentFormatResource;
use App\Http\Resources\UserResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $documentType = $this->documentType;
        $documentFormat = new DocumentFormatResource($documentType->documentFormat);

        return [
            'id' => $this->id,
            'type' => $documentType->name,
            'type_slug' => $documentType->slug,
            'format' => $documentFormat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'completed' => $this->locked,
            'user' => new UserResource($this->user),
            'template' => route('documents.template', ['document' => $this->id])
        ];
    }
}
