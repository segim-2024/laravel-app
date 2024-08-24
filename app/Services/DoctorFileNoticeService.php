<?php

namespace App\Services;

use App\DTOs\CreateDoctorFileNoticeDTO;
use App\DTOs\UpdateDoctorFileNoticeDTO;
use App\Models\DoctorFileNotice;
use App\Repositories\Interfaces\DoctorFileNoticeRepositoryInterface;
use App\Services\Interfaces\DoctorFileNoticeServiceInterface;
use Illuminate\Support\Collection;

class DoctorFileNoticeService implements DoctorFileNoticeServiceInterface {
    public function __construct(
        protected DoctorFileNoticeRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(bool $isWhale, int|string $id): ?DoctorFileNotice
    {
        return $this->repository->find($isWhale, $id);
    }

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
    public function create(CreateDoctorFileNoticeDTO $DTO): DoctorFileNotice
    {
        return $this->repository->save($DTO);
    }

    /**
     * @inheritDoc
     */
    public function update(UpdateDoctorFileNoticeDTO $DTO): DoctorFileNotice
    {
        return $this->repository->updateNotice($DTO);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileNotice $notice): void
    {
        $this->repository->delete($notice);
    }
}
