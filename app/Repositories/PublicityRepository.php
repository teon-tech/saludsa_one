<?php

namespace App\Repositories;


use App\Models\Publicity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Config;


class PublicityRepository
{

    /**
     * @return Builder[]|Collection
     */
    public function findAll()
    {
        $query = Publicity::query();
        $query->where('status', Config::get('constants.active_status'));
        return $query->get();
    }
}
