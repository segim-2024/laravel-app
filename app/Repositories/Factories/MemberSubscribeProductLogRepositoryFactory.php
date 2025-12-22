<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberSubscribeProductRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberSubscribeProductLogRepository;
use App\Repositories\Eloquent\WhaleMemberSubscribeProductLogRepository;
use App\Repositories\Interfaces\MemberSubscribeProductLogRepositoryInterface;

class MemberSubscribeProductLogRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberSubscribeProductLogRepositoryInterface
     * @throws MemberSubscribeProductRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberSubscribeProductLogRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberSubscribeProductLogRepository::class),
            $member instanceof Member => app(MemberSubscribeProductLogRepository::class),
            default => throw new MemberSubscribeProductRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}