<?php

namespace App\Services\Interfaces;

use App\DTOs\CreateDoctorFileNoticeDTO;
use App\DTOs\UpdateDoctorFileNoticeDTO;
use App\Models\DoctorFileNotice;
use Illuminate\Support\Collection;

interface DoctorFileNoticeServiceInterface
{
    /**
     * @param bool $isWhale
     * @param string|int $id
     * @return DoctorFileNotice|null
     */
    public function find(bool $isWhale, string|int $id): ?DoctorFileNotice;

    /**
     * @param bool $isWhale
     * @return Collection
     */
    public function getList(bool $isWhale): Collection;

    /**
     * @param CreateDoctorFileNoticeDTO $DTO
     * @return DoctorFileNotice
     */
    public function create(CreateDoctorFileNoticeDTO $DTO): DoctorFileNotice;

    /**
     * @param UpdateDoctorFileNoticeDTO $DTO
     * @return DoctorFileNotice
     */
    public function update(UpdateDoctorFileNoticeDTO $DTO): DoctorFileNotice;

    /**
     * @param DoctorFileNotice $notice
     * @return void
     */
    public function delete(DoctorFileNotice $notice): void;
}
