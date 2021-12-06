<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\DocumentFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentType extends Model
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

    /**
     * Get the format that owns the document type.
     */
    public function documentFormat()
    {
        return $this->belongsTo(DocumentFormat::class);
    }
}
