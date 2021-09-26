<?php


namespace App\Processes;


use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;

class CategoryProcess
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function findCategories()
    {
        return $this->buildTreeCategory(1);
    }

    public function buildTreeCategory($level = 1, $parentCategoryId = null, $categoryIds = null)
    {
        $children = [];
        $subcategories = $this->categoryRepository->findCategoriesByLevel($level, $parentCategoryId, $categoryIds);

        foreach ($subcategories as $category) {
            $itemCategory = CategoryResource::make($category)->jsonSerialize();
            $itemCategory['children'] = $this->buildTreeCategory($level + 1, $category->id, $categoryIds);
            $children[] = $itemCategory;
        }
        return $children;
    }

    /**
     * @param $categoryId
     * @param string $method
     * @return array
     */
    public function getChildrenIds($categoryId, $method = 'asc')
    {
        $ids = [$categoryId];
        $categoryParent = $this->categoryRepository->findBy($categoryId);
        if ($method == 'asc') {
            $level = $categoryParent->level + 1;
        } else {
            $level = $categoryParent->level - 1;
        }
        $subcategories = $this->categoryRepository->findCategoriesByLevel($level, $categoryId);
        foreach ($subcategories as $category) {
            $ids[] = $category->id;
            $ids = array_merge($ids, $this->getChildrenIds($category->id));
        }
        return $ids;
    }

}