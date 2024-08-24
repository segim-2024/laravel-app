<?php

namespace App\Services\Interfaces;

use App\DTOs\GetListDoctorFileSeriesDTO;
use App\Models\DoctorFileSeries;
use Illuminate\Support\Collection;

interface DoctorFileSeriesServiceInterface
{
    /**
     * @param GetListDoctorFileSeriesDTO $DTO
     * @return Collection
     */
    public function getList(GetListDoctorFileSeriesDTO $DTO): Collection;

    /**
     * @param string $uuid
     * @return DoctorFileSeries|null
     */
    public function find(string $uuid): ?DoctorFileSeries;

    /**
     * @param DoctorFileSeries $series
     * @return void
     */
    public function delete(DoctorFileSeries $series):void;
}
