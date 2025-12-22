<?php

namespace App\Repositories\Factories;

use App\Exceptions\MemberCardRepositoryFactoryException;
use App\Models\Interfaces\MemberInterface;
use App\Models\Member;
use App\Models\WhaleMember;
use App\Repositories\Eloquent\MemberCardRepository;
use App\Repositories\Eloquent\WhaleMemberCardRepository;
use App\Repositories\Interfaces\MemberCardRepositoryInterface;

class MemberCardRepositoryFactory
{
    /**
     * @param MemberInterface $member
     * @return MemberCardRepositoryInterface
     * @throws MemberCardRepositoryFactoryException
     */
    public function create(MemberInterface $member): MemberCardRepositoryInterface
    {
        return match (true) {
            $member instanceof WhaleMember => app(WhaleMemberCardRepository::class),
            $member instanceof Member => app(MemberCardRepository::class),
            default => throw new MemberCardRepositoryFactoryException(sprintf('Unsupported member type: %s', get_class($member))),
        };
    }
}