<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;

class CategoryController
{
    public function getCategories(CategoryService $categoryService)
    {
        $categories = $categoryService->getCategories();

        return response()->json(['status' => true, 'data' => $categories], 200);
    }

}
