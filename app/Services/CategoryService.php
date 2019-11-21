<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepository;

class CategoryService
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories
     *
     * @return mixed
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }
}