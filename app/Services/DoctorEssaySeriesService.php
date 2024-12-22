<?php

namespace App\Services;

use App\Models\DoctorEssaySeries;
use App\Models\DoctorEssayVolume;
use App\Repositories\Interfaces\DoctorEssaySeriesRepositoryInterface;
use App\Services\Interfaces\DoctorEssaySeriesServiceInterface;
use App\Services\Interfaces\DoctorEssayVolumeServiceInterface;
use Illuminate\Support\Collection;

class DoctorEssaySeriesService implements DoctorEssaySeriesServiceInterface {
    public function __construct(
        protected DoctorEssayVolumeServiceInterface $volumeService,
        protected DoctorEssaySeriesRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function getList(bool $isWhale): Collection
    {
        return $this->repository->getList($isWhale);
    }

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssaySeries
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uuid): void
    {
        $series = $this->repository->find($uuid);
        if ($series === null) {
            return;
        }

        $series->volumes
            ->each(fn(DoctorEssayVolume $volume) => $this->volumeService->delete($volume->volume_uuid));

        $this->repository->delete($series);
    }
}
