<?php


namespace App\Repositories;


use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProviderRepository
{

    /**
     * @param $value
     * @param string $attr
     * @return Builder|Model|object|null
     */
    public function findBy($value, $attr = 'id')
    {
        return Provider::query()
            ->where($attr, $value)
            ->first();
    }
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll($params = [])
    {
        $size = isset($params['size']) && $params['size'] ? $params['size'] : 10;

        $query = Provider::query();
        $providers = $query -> where('status' , 'ACTIVE');

        return $providers->paginate($size);
    }


}