<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Models\Document;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class DocumentManager
{
    public function getPaginatedIndex(array $filters, int $limit): LengthAwarePaginator
    {
        $query = Document::select('*');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['slug'])) {
            $query->whereRelation('documentType', 'slug', $filters['slug']);
        }

        if (!empty($filters['created_at'])) {
            $query->where('created_at', '>', $filters['created_at']);
        }

        if (!empty($filters['updated_at'])) {
            $query->where('updated_at', '>', $filters['updated_at']);
        }

        // cache results
        $queryParams = request()->query();
        ksort($queryParams);
        $cacheKey = 'documents_list.'. http_build_query($queryParams);

        return Cache::remember($cacheKey, 300, function () use ($query, $limit) {
            return $query->paginate($limit);
        });
    }
}
