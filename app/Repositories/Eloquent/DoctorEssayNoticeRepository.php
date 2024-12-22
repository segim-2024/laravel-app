<?php
namespace App\Repositories\Eloquent;

use App\Models\DoctorEssayNotice;
use App\Repositories\Interfaces\DoctorEssayNoticeRepositoryInterface;
use Illuminate\Support\Collection;

class DoctorEssayNoticeRepository extends BaseRepository implements DoctorEssayNoticeRepositoryInterface
{
    public function __construct(DoctorEssayNotice $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function find(bool $isWhale, int $id): ?DoctorEssayNotice
    {
        return DoctorEssayNotice::where('is_whale', '=', $isWhale)->find($id);
    }

    /**
     * @inheritDoc
     */
    public function getList(bool $isWhale): Collection
    {
        return DoctorEssayNotice::where('is_whale', '=', $isWhale)->orderBy('created_at', 'DESC')->get();
    }
}
