<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateDoctorEssayNoticeDTO;
use App\DTOs\DeleteDoctorEssayNoticeDTO;
use App\DTOs\UpdateDoctorEssayNoticeDTO;
use App\Exceptions\DoctorEssayNoticeNotFoundException;
use App\Models\DoctorEssayNotice;
use Illuminate\Support\Collection;

interface DoctorEssayNoticeServiceInterface
{
    /**
     * @param bool $isWhale
     * @param int $id
     * @return DoctorEssayNotice|null
     */
    public function find(bool $isWhale, int $id): ?DoctorEssayNotice;

    /**
     * @param bool $isWhale
     * @return Collection
     */
    public function getList(bool $isWhale): Collection;

    /**
     * @param CreateDoctorEssayNoticeDTO $DTO
     * @return DoctorEssayNotice
     */
    public function create(CreateDoctorEssayNoticeDTO $DTO): DoctorEssayNotice;

    /**
     * @param UpdateDoctorEssayNoticeDTO $DTO
     * @return DoctorEssayNotice
     * @throws DoctorEssayNoticeNotFoundException
     */
    public function update(UpdateDoctorEssayNoticeDTO $DTO): DoctorEssayNotice;

    /**
     * @param DeleteDoctorEssayNoticeDTO $DTO
     * @return void
     */
    public function delete(DeleteDoctorEssayNoticeDTO $DTO): void;
}
