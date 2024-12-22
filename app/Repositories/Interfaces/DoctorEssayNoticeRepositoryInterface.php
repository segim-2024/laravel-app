<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorEssayNotice;
use Illuminate\Support\Collection;

interface DoctorEssayNoticeRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param bool $isWhale
     * @param int $id
     * @return DoctorEssayNotice|null
     */
    public function find(bool $isWhale, int $id): ?DoctorEssayNotice;

    /**
     * @param bool $isWhale
     * @return Collection
     */
    public function getList(bool $isWhale): Collection;
}
