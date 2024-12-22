<?php

namespace App\Services;

use App\DTOs\CreateDoctorEssayNoticeDTO;
use App\DTOs\DeleteDoctorEssayNoticeDTO;
use App\DTOs\UpdateDoctorEssayNoticeDTO;
use App\Exceptions\DoctorEssayNoticeNotFoundException;
use App\Models\DoctorEssayNotice;
use App\Repositories\Interfaces\DoctorEssayNoticeRepositoryInterface;
use App\Services\Interfaces\DoctorEssayNoticeServiceInterface;
use Illuminate\Support\Collection;

class DoctorEssayNoticeService implements DoctorEssayNoticeServiceInterface {
    public function __construct(
        protected DoctorEssayNoticeRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(bool $isWhale, int $id): ?DoctorEssayNotice
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
    public function create(CreateDoctorEssayNoticeDTO $DTO): DoctorEssayNotice
    {
        return $this->repository->create($DTO->toModelAttributes());
    }

    /**
     * @inheritDoc
     */
    public function update(UpdateDoctorEssayNoticeDTO $DTO): DoctorEssayNotice
    {
        $notice = $this->find($DTO->isWhale, $DTO->noticeId);
        if (! $notice) {
            throw new DoctorEssayNoticeNotFoundException("공지사항이 존재하지 않습니다.");
        }

        return $this->repository->update($notice, $DTO->toModelAttributes());
    }

    /**
     * @inheritDoc
     */
    public function delete(DeleteDoctorEssayNoticeDTO $DTO): void
    {
        $notice = $this->find($DTO->isWhale, $DTO->noticeId);
        if ($notice) {
            $this->repository->delete($notice);
        }
    }
}
