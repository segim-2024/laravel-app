<?php

namespace App\Services;

use App\DTOs\CreateFileDTO;
use App\DTOs\UpdateDoctorEssayVolumeDescriptionDTO;
use App\DTOs\UpdateDoctorEssayVolumeIsPublishedDTO;
use App\DTOs\UpdateDoctorEssayVolumePosterDTO;
use App\DTOs\UpdateDoctorEssayVolumeUrlDTO;
use App\Exceptions\DoctorEssayVolumeNotFoundException;
use App\Jobs\DeleteFileFromS3Job;
use App\Models\DoctorEssayLesson;
use App\Models\DoctorEssayVolume;
use App\Repositories\Interfaces\DoctorEssayVolumeRepositoryInterface;
use App\Services\Interfaces\DoctorEssayLessonServiceInterface;
use App\Services\Interfaces\DoctorEssayVolumeServiceInterface;
use App\Services\Interfaces\FileServiceInterface;

class DoctorEssayVolumeService implements DoctorEssayVolumeServiceInterface {
    public function __construct(
        protected DoctorEssayLessonServiceInterface $lessonService,
        protected FileServiceInterface $fileService,
        protected DoctorEssayVolumeRepositoryInterface $repository
    ) {}

    /**
     * @inheritDoc
     */
    public function find(string $uuid): ?DoctorEssayVolume
    {
        return $this->repository->find($uuid);
    }

    /**
     * @inheritDoc
     */
    public function updatePoster(UpdateDoctorEssayVolumePosterDTO $DTO): DoctorEssayVolume
    {
        $volume = $this->find($DTO->volumeUuid);
        if (! $volume) {
            throw new DoctorEssayVolumeNotFoundException('해당 논술 박사 볼륨을 찾을 수 없습니다.');
        }

        if ($volume->poster) {
            DeleteFileFromS3Job::dispatch($volume->poster)->afterCommit();
        }

        $file = $this->fileService->create(CreateFileDTO::createFromDoctorEssayVolume($volume->volume_uuid, $DTO->poster));
        return $this->repository->updatePoster($volume, $file->uuid);
    }

    /**
     * @inheritDoc
     */
    public function updateDescription(UpdateDoctorEssayVolumeDescriptionDTO $DTO): DoctorEssayVolume
    {
        $volume = $this->find($DTO->volumeUuid);
        if (! $volume) {
            throw new DoctorEssayVolumeNotFoundException('해당 논술 박사 볼륨을 찾을 수 없습니다.');
        }

        return $this->repository->updateDescription($volume, $DTO->description);
    }

    /**
     * @inheritDoc
     */
    public function updateUrl(UpdateDoctorEssayVolumeUrlDTO $DTO): DoctorEssayVolume
    {
        $volume = $this->find($DTO->volumeUuid);
        if (! $volume) {
            throw new DoctorEssayVolumeNotFoundException('해당 논술 박사 볼륨을 찾을 수 없습니다.');
        }

        return $this->repository->updateUrl($volume, $DTO->url);
    }

    /**
     * @inheritDoc
     */
    public function updateIsPublished(UpdateDoctorEssayVolumeIsPublishedDTO $DTO): DoctorEssayVolume
    {
        $volume = $this->find($DTO->volumeUuid);
        if (! $volume) {
            throw new DoctorEssayVolumeNotFoundException('해당 논술 박사 볼륨을 찾을 수 없습니다.');
        }

        return $this->repository->updateIsPublished($volume, $DTO->isPublished);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uuid): void
    {
        $volume = $this->find($uuid);
        if (! $volume) {
            return;
        }

        if ($volume->poster) {
            DeleteFileFromS3Job::dispatch($volume->poster)->afterCommit();
        }

        $volume->lessons
            ->each(fn(DoctorEssayLesson $lesson) => $this->lessonService->delete($lesson->lesson_uuid));
        $this->repository->delete($volume);
    }
}
