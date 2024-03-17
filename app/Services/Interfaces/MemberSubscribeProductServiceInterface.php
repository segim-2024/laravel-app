<?php

namespace App\Services\Interfaces;

use App\DTOs\UpsertMemberSubscribeProductDTO;
use App\Models\MemberSubscribeProduct;
use Illuminate\Support\Collection;

interface MemberSubscribeProductServiceInterface
{
    /**
     * @param UpsertMemberSubscribeProductDTO $DTO
     * @return Collection
     */
    public function subscribe(UpsertMemberSubscribeProductDTO $DTO):Collection;
}
