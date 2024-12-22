<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorEssaySeries;
use Illuminate\Support\Collection;

interface DoctorEssaySeriesRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param bool $isWhale
     * @return Collection
     */
    public function getList(bool $isWhale): Collection;

    /**
     * 시리즈 데이터를 찾습니다.
     *
     * @param string $uuid
     * @return DoctorEssaySeries|null
     */
    public function find(string $uuid): ?DoctorEssaySeries;
}
