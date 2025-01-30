<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberCashRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberCashRepository;
use App\Repositories\Eloquent\WhaleMemberCashRepository;
use App\Repositories\Interfaces\MemberCashRepositoryInterface;

class MemberCashRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberCashRepositoryInterface
     * @throws MemberCashRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberCashRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberCashRepository::class),
            $member instanceof Member => app(MemberCashRepository::class),
            default => throw new MemberCashRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}
