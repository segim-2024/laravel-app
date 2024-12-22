<?php

namespace App\Services\Interfaces;

use App\Models\DoctorEssaySeries;
use Illuminate\Support\Collection;

interface DoctorEssaySeriesServiceInterface
{
    /**
     * @param bool $isWhale
     * @return Collection
     */
    public function getList(bool $isWhale): Collection;

    /**
     * @param string $uuid
     * @return DoctorEssaySeries|null
     */
    public function find(string $uuid): ?DoctorEssaySeries;

    /**
     * @param string $uuid
     * @return void
     */
    public function delete(string $uuid):void;
}
