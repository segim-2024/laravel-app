<?php
namespace App\Repositories\Eloquent;

use App\DTOs\CreateDoctorFileNoticeDTO;
use App\DTOs\UpdateDoctorFileNoticeDTO;
use App\Models\DoctorFileNotice;
use App\Repositories\Interfaces\DoctorFileNoticeRepositoryInterface;
use Illuminate\Support\Collection;

class DoctorFileNoticeRepository extends BaseRepository implements DoctorFileNoticeRepositoryInterface
{
    public function __construct(DoctorFileNotice $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(bool $isWhale, int|string $id): ?DoctorFileNotice
    {
        return DoctorFileNotice::where('is_whale', '=', $isWhale)->find($id);
    }

    /**
     * @param bool $isWhale
     * @inheritDoc
     */
    public function getList(bool $isWhale): Collection
    {
        return DoctorFileNotice::where('is_whale', '=', $isWhale)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * @inheritDoc
     */
    public function save(CreateDoctorFileNoticeDTO $DTO): DoctorFileNotice
    {
        $notice = new DoctorFileNotice();
        $notice->member_id = $DTO->member->mb_id;
        $notice->title = $DTO->title;
        $notice->content = $DTO->content;
        $notice->is_whale = $DTO->isWhale;
        $notice->save();
        return $notice;
    }

    /**
     * @inheritDoc
     */
    public function updateNotice(UpdateDoctorFileNoticeDTO $DTO): DoctorFileNotice
    {

        $notice = $DTO->notice;
        $notice->title = $DTO->title;
        $notice->content = $DTO->content;
        $notice->save();
        return $notice;
    }
}
