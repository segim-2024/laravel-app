<?php
namespace App\Repositories\Interfaces;

use App\Models\DoctorFileSeries;

interface DoctorFileSeriesRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 시리즈 데이터를 찾습니다.
     *
     * @param string $uuid
     * @return DoctorFileSeries|null
     */
    public function find(string $uuid): ?DoctorFileSeries;
}
