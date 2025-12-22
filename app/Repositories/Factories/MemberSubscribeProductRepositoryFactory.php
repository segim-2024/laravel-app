<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberSubscribeProductRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberSubscribeProductRepository;
use App\Repositories\Eloquent\WhaleMemberSubscribeProductRepository;
use App\Repositories\Interfaces\MemberSubscribeProductRepositoryInterface;

class MemberSubscribeProductRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberSubscribeProductRepositoryInterface
     * @throws MemberSubscribeProductRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberSubscribeProductRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberSubscribeProductRepository::class),
            $member instanceof Member => app(MemberSubscribeProductRepository::class),
            default => throw new MemberSubscribeProductRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}