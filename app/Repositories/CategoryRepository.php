<?php


namespace App\Repositories;


use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryRepository
{

    /**
     * @param int $level
     * @param null $parentId
     * @param null $childrenIds
     * @return array|Builder[]|Collection
     */
    public function findCategoriesByLevel($level = 1, $parentId = null, $childrenIds = null)
    {
        if ($level > Category::MAX_LEVEL || $level == 0) {
            return [];
        }
        $query = Category::query();
        $query->where('status', '=', Category::STATUS_ACTIVE);
        $query->where('level', '=', $level);

        if ($parentId) {
            $query->where('parent_category_id', '=', $parentId);
        }
        if ($childrenIds != null) {
            $query->whereIn('id', $childrenIds);
        }
        $query->orderBy('weight', 'asc');
        $result = $query->get();
        return $result;
    }

    /**
     * @param $value
     * @param string $attr
     * @return Builder|Model|object|null
     */
    public function findBy($value, $attr = 'id')
    {
        return Category::query()
            ->where($attr, $value)
            ->first();
    }

}