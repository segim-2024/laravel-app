<?php
namespace App\Repositories\Interfaces;

use App\DTOs\MemberSubscribeProductLogDTO;

interface MemberSubscribeProductLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param MemberSubscribeProductLogDTO $DTO
     * @return void
     */
    public function save(MemberSubscribeProductLogDTO $DTO): void;
}
