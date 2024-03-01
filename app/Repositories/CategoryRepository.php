<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    public function getCategories(): Collection
    {
        return DB::table('categories')
            ->where('is_active', '=', 1)
            ->orderBy('name', 'ASC')
            ->get(['id', 'is_active', 'name', 'parent_category_id', 'created_at', 'updated_at']);
    }
}
