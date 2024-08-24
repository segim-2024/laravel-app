<?php
namespace App\Repositories\Interfaces;

use App\DTOs\CreateDoctorFileNoticeDTO;
use App\DTOs\UpdateDoctorFileNoticeDTO;
use App\Models\DoctorFileNotice;
use Illuminate\Support\Collection;

interface DoctorFileNoticeRepositoryInterface extends BaseRepositoryInterface
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
    public function save(CreateDoctorFileNoticeDTO $DTO): DoctorFileNotice;

    /**
     * @param UpdateDoctorFileNoticeDTO $DTO
     * @return DoctorFileNotice
     */
    public function updateNotice(UpdateDoctorFileNoticeDTO $DTO): DoctorFileNotice;
}
