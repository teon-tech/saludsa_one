<?php

namespace App\Http\Controllers\Api;

use App\Processes\CategoryProcess;

class CategoryController extends ApiBaseController
{
    private $categoryProcess;

    public function __construct(CategoryProcess $categoryProcess)
    {
        $this->categoryProcess = $categoryProcess;
    }

    public function findCategories()
    {
        return $this->categoryProcess->findCategories();
    }

}