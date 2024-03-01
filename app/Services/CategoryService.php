<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $categoryRepository) {}


    protected function groupByParentCategoryId($data) {
        $grouped = [];

        // First, index items by their ID
        $indexedData = [];
        foreach ($data as $item) {
            $indexedData[$item['id']] = $item;
        }

        // Group items by their parentCategoryId
        foreach ($indexedData as $id => $item) {
            $parentId = $item['parentCategoryId'];

            // If parentCategoryId is null, treat it as a root level item
            if ($parentId === null) {
                $grouped[$id] = $item;
            } else {
                // Add the item as a child to its parent
                $grouped[$parentId]['children'][] = $item;
            }
        }

        // Return the values to remove the keys indexed by IDs
        return array_values($grouped);
    }
    public function getCategories(): array
    {
        $categories = array_map(
            function ($category) {
                return CategoryMapper::fromDTO((object)$category);
            },
            $this->categoryRepository->getCategories()->toArray()
        );

        return $this->groupByParentCategoryId($categories);
    }
}
