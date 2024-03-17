<?php

namespace App\Services\Interfaces;

use App\Models\Member;
use App\Models\Product;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    /**
     * @param string|int $id
     * @return Product|null
     */
    public function find(string|int $id):?Product;

    /**
     * @param Member $member
     * @return Collection
     */
    public function getList(Member $member):Collection;
}
