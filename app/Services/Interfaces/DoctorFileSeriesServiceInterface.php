<?php

namespace App\Services\Interfaces;

use App\Models\DoctorFileSeries;

interface DoctorFileSeriesServiceInterface
{
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
