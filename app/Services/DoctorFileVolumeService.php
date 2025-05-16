<?php

namespace App\Services;

use App\DTOs\CreateFileDTO;
use App\DTOs\UpdateDoctorFileVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorFileVolumeIsPublishedDTO;
use App\DTOs\UpdateDoctorFileVolumePosterDTO;
use App\DTOs\UpdateDoctorFileVolumeUrlDTO;
use App\Exceptions\DoctorFileVolumeNotFoundException;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\DoctorFileLesson;
use App\Models\DoctorFileVolume;
use App\Repositories\Interfaces\DoctorFileVolumeRepositoryInterface;
use App\Services\Interfaces\DoctorFileLessonServiceInterface;
use App\Services\Interfaces\DoctorFileVolumeServiceInterface;
use App\Services\Interfaces\FileServiceInterface;

class DoctorFileVolumeService implements DoctorFileVolumeServiceInterface {
    public function __construct(
        protected DoctorFileLessonServiceInterface $lessonService,
        protected FileServiceInterface $fileService,
        protected DoctorFileVolumeRepositoryInterface $repository,
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorFileVolume
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function updatePoster(UpdateDoctorFileVolumePosterDTO $DTO): DoctorFileVolume
    {
        if ($DTO->volume->poster) {
            DeleteFileFromS3Job::dispatch($DTO->volume->poster)->afterCommit();
        }

        $file = $this->fileService->create(CreateFileDTO::createFromDoctorFileVolume($DTO->volume, $DTO->poster));
        return $this->repository->updatePoster($DTO->volume, $file);
    }

    /**
     * @inheritDoc
     */
    public function updateDescription(UpdateDoctorFileVolumeDescriptionDTO $DTO): DoctorFileVolume
    {
        return $this->repository->updateDescription($DTO);
    }

    /**
     * @inheritDoc
     */
    public function updateIsPublished(UpdateDoctorFileVolumeIsPublishedDTO $DTO): DoctorFileVolume
    {
        return $this->repository->updateIsPublished($DTO);
    }

    /**
     * @inheritDoc
     */
    public function updateUrl(UpdateDoctorFileVolumeUrlDTO $DTO): DoctorFileVolume
    {
        $volume = $this->find($DTO->volumeUuid);
        if (! $volume) {
            throw new DoctorFileVolumeNotFoundException('해당 자료 박사 볼륨을 찾을 수 없습니다.');
        }

        return $this->repository->updateUrl($volume, $DTO->url);
    }

    /**
     * @inheritDoc
     */
    public function delete(DoctorFileVolume $volume): void
    {
        if ($volume->poster) {
            DeleteFileFromS3Job::dispatch($volume->poster)->afterCommit();
        }

        $volume->lessons->each(fn(DoctorFileLesson $lesson) => $this->lessonService->delete($lesson));
        $this->repository->delete($volume);
    }
}
