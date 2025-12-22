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
        $log->subscribe_id = $DTO->subscribe->getId();
        $log->member_id = $DTO->subscribe->getMemberId();
        $log->card_id = $DTO->subscribe->getCardId();
        $log->product_id = $DTO->subscribe->getProductId();
        $log->type = $DTO->type;
        $log->content = $DTO->content;
        $log->save();
    }
}
