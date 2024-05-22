<?php
namespace App\Repositories\Eloquent;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\Models\MemberSubscribeProductLog;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;

class MemberSubscribeProductLogRepository extends BaseRepository implements MemberSubscribeProductLogRepositoryInterface
{
    public function __construct(MemberSubscribeProductLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function save(MemberSubscribeProductLogDTO $DTO): void
    {
        $log = new MemberSubscribeProductLog();
        $log->subscribe_id = $DTO->subscribe->id;
        $log->member_id = $DTO->subscribe->member_id;
        $log->card_id = $DTO->subscribe->card_id;
        $log->product_id = $DTO->subscribe->product_id;
        $log->type = $DTO->type;
        $log->content = $DTO->content;
        $log->save();
    }
}
