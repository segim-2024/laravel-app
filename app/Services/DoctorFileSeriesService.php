<?php

namespace App\Services;

use App\Http\Resources\DoctorFileSeriesRootResource;
use App\Models\DoctorFileSeries;
use App\Repositories\Interfaces\DoctorFileSeriesRepositoryInterface;
use App\Services\Interfaces\DoctorFileSeriesServiceInterface;
use App\Services\Interfaces\DoctorFileVolumeServiceInterface;
use Illuminate\Support\Collection;

class DoctorFileSeriesService implements DoctorFileSeriesServiceInterface {
    public function __construct(
        protected DoctorFileVolumeServiceInterface $volumeService,
        protected DoctorFileSeriesRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileSeries
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileSeries $series): void
    {
        $series->volumes->each(fn($volume) => $this->volumeService->delete($volume));
        $this->repository->delete($series);
    }
}
