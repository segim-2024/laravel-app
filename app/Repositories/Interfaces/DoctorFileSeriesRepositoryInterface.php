<?php
namespace App\Repositories\Interfaces;

use App\DTOs\GetListDoctorFileSeriesDTO;
use App\Models\DoctorFileSeries;
use Illuminate\Support\Collection;

interface DoctorFileSeriesRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param GetListDoctorFileSeriesDTO $DTO
     * @return Collection
     */
    public function getList(GetListDoctorFileSeriesDTO $DTO):Collection;

    /**
     * 시리즈 데이터를 찾습니다.
     *
     * @param string $uuid
     * @return DoctorFileSeries|null
     */
    public function find(string $uuid): ?DoctorFileSeries;
}
