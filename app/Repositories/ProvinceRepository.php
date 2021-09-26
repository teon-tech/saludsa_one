<?php

namespace App\Repositories;

use App\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProvinceRepository
{
    
    public function findAll()
    {
        $query = Province::query()
            ->where('status', 'ACTIVE');
        return $query->get();
    }
}
