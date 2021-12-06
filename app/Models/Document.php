<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentType;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'locked'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        $clearCacheClosure = function () {
            Cache::flush();
        };

        static::saved($clearCacheClosure);
        static::deleted($clearCacheClosure);
    }

    /**
     * Get the user that owns the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the document type that owns the document.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function scopeIsUpdatable($query)
    {
        $query->whereRelation('documentType.documentFormat', 'e_signable', true)
            ->where('locked', false);
    }

    public function toArray()
    {
        $documentType = $this->documentType;

        return [
            'id' => $this->id,
            'type' => $documentType->name,
            'type_slug' => $documentType->slug,
            'format' => $documentType->documentFormat->toArray(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'completed' => $this->locked,
        ];
    }
}
