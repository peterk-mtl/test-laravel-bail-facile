<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentFormat extends Model
{
    use Sluggable;
    use HasFactory;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function toArray()
    {
        $documentType = $this->documentType;

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'e_signable' => $this->e_signable,
            'postable' => $this->postable,
            'emailable' => $this->emailable,
        ];
    }
}
