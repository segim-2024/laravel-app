<?php

namespace App\Repositories\Eloquent;

use App\DTOs\MemberSubscribeProductLogDTO;
use App\Models\WhaleMemberSubscribeProductLog;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;

class WhaleMemberSubscribeProductLogRepository extends BaseRepository implements MemberSubscribeProductLogRepositoryInterface
{
    public function __construct(WhaleMemberSubscribeProductLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @inheritDoc
     */
    public function save(MemberSubscribeProductLogDTO $DTO): void
    {
        $log = new WhaleMemberSubscribeProductLog();
        $log->subscribe_id = $DTO->subscribe->getId();
        $log->type = $DTO->type;
        $log->message = $DTO->content;
        $log->save();
    }
}